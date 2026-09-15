<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCapacityExpansionRequest;
use App\Http\Requests\ProposeCapacityExpansionRequest;
use App\Http\Requests\RespondCapacityExpansionProposalRequest;
use App\Models\CapacityExpansionRequest;
use App\Models\Wedding;
use App\Models\WeddingAudit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CapacityExpansionRequestController extends Controller
{
    use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;

    public function store(StoreCapacityExpansionRequest $request, Wedding $wedding)
    {
        $this->authorize('view', $wedding);
        $item = $wedding->capacityExpansionRequests()->create(['requested_by' => $request->user()->id, ...$request->validated()]);
        WeddingAudit::create(['wedding_id' => $wedding->id, 'actor_id' => $request->user()->id, 'action' => 'capacity_expansion.requested', 'subject_type' => CapacityExpansionRequest::class, 'subject_id' => $item->id, 'after' => ['requested_count' => $item->requested_count]]);
        return back()->with('success', 'La solicitud de ampliación fue enviada a la empresa.');
    }

    public function propose(ProposeCapacityExpansionRequest $request, Wedding $wedding, CapacityExpansionRequest $capacityRequest)
    {
        abort_unless($capacityRequest->wedding_id === $wedding->id, 404);
        $data = $request->validated();
        DB::transaction(function () use ($capacityRequest, $data, $request, $wedding) {
            $item = CapacityExpansionRequest::query()->lockForUpdate()->findOrFail($capacityRequest->id);
            abort_unless(in_array($item->status, ['pending', 'proposed'], true), 422);
            $item->update(['status' => 'proposed', 'additional_cost' => $data['additional_cost'], 'decision_reason' => $data['decision_reason'] ?? null, 'proposal_version' => $item->proposal_version + 1, 'proposed_by' => $request->user()->id, 'proposed_at' => now(), 'responded_by' => null, 'responded_at' => null]);
            WeddingAudit::create(['wedding_id' => $wedding->id, 'actor_id' => $request->user()->id, 'action' => 'capacity_expansion.proposed', 'subject_type' => CapacityExpansionRequest::class, 'subject_id' => $item->id, 'after' => ['version' => $item->proposal_version, 'additional_cost' => $item->additional_cost]]);
        });
        return back()->with('success', 'Propuesta de ampliación enviada a la pareja.');
    }

    public function respond(RespondCapacityExpansionProposalRequest $request, Wedding $wedding, CapacityExpansionRequest $capacityRequest)
    {
        $this->authorize('view', $wedding);
        abort_unless($capacityRequest->wedding_id === $wedding->id, 404);
        $membership = $request->user()->weddingMemberships()->where('wedding_id', $wedding->id)->first();
        abort_unless($request->user()->role === 'admin' || ($membership && data_get($membership->permissions, 'approve_proposals')), 403);
        $data = $request->validated();
        DB::transaction(function () use ($capacityRequest, $data, $request, $wedding) {
            $item = CapacityExpansionRequest::query()->lockForUpdate()->findOrFail($capacityRequest->id);
            abort_unless($item->status === 'proposed', 422);
            $item->update(['status' => $data['decision'], 'decision_reason' => $data['decision_reason'] ?? $item->decision_reason, 'responded_by' => $request->user()->id, 'responded_at' => now()]);
            WeddingAudit::create(['wedding_id' => $wedding->id, 'actor_id' => $request->user()->id, 'action' => 'capacity_expansion.proposal_responded', 'subject_type' => CapacityExpansionRequest::class, 'subject_id' => $item->id, 'after' => ['status' => $item->status, 'version' => $item->proposal_version]]);
        });
        return back()->with('success', $data['decision'] === 'accepted' ? 'Propuesta aceptada; queda pendiente la autorización final de la empresa.' : 'Propuesta rechazada.');
    }

    public function resolve(Request $request, Wedding $wedding, CapacityExpansionRequest $capacityRequest)
    {
        abort_unless($request->user()->role === 'admin', 403);
        abort_unless($capacityRequest->wedding_id === $wedding->id, 404);
        $data = $request->validate(['decision' => ['required', 'in:approved,rejected'], 'decision_reason' => ['nullable', 'string', 'max:1000'], 'additional_cost' => ['nullable', 'numeric', 'min:0']]);
        DB::transaction(function () use ($wedding, $capacityRequest, $data, $request) {
            $item = CapacityExpansionRequest::query()->lockForUpdate()->findOrFail($capacityRequest->id);
            if ($data['decision'] === 'approved') {
                abort_unless($item->status === 'accepted', 422, 'La pareja debe aceptar la propuesta antes de autorizar la ampliación.');
                if ($wedding->authorized_capacity === null) throw ValidationException::withMessages(['decision' => 'No se puede ampliar un cupo cuya capacidad autorizada aún es desconocida.']);
                $wedding->increment('authorized_capacity', $item->requested_count);
            } else {
                abort_unless(in_array($item->status, ['pending', 'proposed', 'accepted'], true), 422);
            }
            $item->update(['status' => $data['decision'], 'decision_reason' => $data['decision_reason'] ?? null, 'additional_cost' => $data['additional_cost'] ?? null, 'decided_by' => $request->user()->id, 'decided_at' => now()]);
            WeddingAudit::create(['wedding_id' => $wedding->id, 'actor_id' => $request->user()->id, 'action' => 'capacity_expansion.resolved', 'subject_type' => CapacityExpansionRequest::class, 'subject_id' => $item->id, 'after' => ['status' => $item->status, 'requested_count' => $item->requested_count]]);
        });
        return back()->with('success', 'Solicitud de ampliación resuelta. No se asignaron pases automáticamente.');
    }
}
