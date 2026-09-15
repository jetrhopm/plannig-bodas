<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResolveAdditionalSeatRequest;
use App\Http\Requests\StoreAdditionalSeatRequest;
use App\Models\AdditionalSeatRequest;
use App\Models\FamilyNotification;
use App\Models\GuestFamily;
use App\Models\Wedding;
use App\Models\WeddingAudit;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AdditionalSeatRequestController extends Controller
{
    use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;

    public function store(StoreAdditionalSeatRequest $request, string $token)
    {
        $family = GuestFamily::where('invite_token_hash', hash('sha256', $token))->whereNull('invite_revoked_at')->firstOrFail();
        $item = $family->additionalSeatRequests()->create($request->validated());
        FamilyNotification::create(['guest_family_id' => $family->id, 'type' => 'additional_seats', 'title' => 'Solicitud de lugares recibida', 'description' => "Solicitaste {$item->requested_count} lugar(es) adicional(es)."]);
        WeddingAudit::create(['wedding_id' => $family->wedding_id, 'action' => 'additional_seats.requested', 'subject_type' => AdditionalSeatRequest::class, 'subject_id' => $item->id, 'after' => ['requested_count' => $item->requested_count]]);
        return back()->with('success', 'Tu solicitud fue enviada; no modifica tus pases mientras esté pendiente.');
    }

    public function resolve(ResolveAdditionalSeatRequest $request, Wedding $wedding, AdditionalSeatRequest $seatRequest)
    {
        $this->authorize('update', $wedding);
        $data = $request->validated();
        DB::transaction(function () use ($wedding, $seatRequest, $data, $request) {
            $lockedWedding = Wedding::query()->lockForUpdate()->findOrFail($wedding->id);
            $lockedRequest = AdditionalSeatRequest::query()->lockForUpdate()->findOrFail($seatRequest->id);
            $family = GuestFamily::query()->lockForUpdate()->findOrFail($lockedRequest->guest_family_id);
            abort_unless($family->wedding_id === $lockedWedding->id && $lockedRequest->status === 'pending', 422);
            $approved = $data['decision'] === 'approved' ? (int) $data['approved_count'] : 0;
            if ($approved > $lockedRequest->requested_count) throw ValidationException::withMessages(['approved_count' => 'No se puede aprobar más de lo solicitado.']);
            if ($approved > 0) {
                if ($lockedWedding->authorized_capacity === null) throw ValidationException::withMessages(['approved_count' => 'No puede evaluarse la disponibilidad sin cupo autorizado.']);
                $assigned = GuestFamily::where('wedding_id', $lockedWedding->id)->lockForUpdate()->sum('current_allocation');
                if ($assigned + $approved > $lockedWedding->authorized_capacity) throw ValidationException::withMessages(['approved_count' => 'Ya no hay lugares suficientes disponibles.']);
                $family->increment('current_allocation', $approved);
            }
            $lockedRequest->update(['status' => $data['decision'], 'approved_count' => $approved ?: null, 'decision_reason' => $data['decision_reason'] ?? null, 'decided_by' => $request->user()->id, 'decided_at' => now()]);
            $description = $data['decision'] === 'approved' ? "Se aprobaron {$approved} lugares. Deberás confirmar su uso antes de ingresar." : ($data['decision_reason'] ?: 'La empresa no pudo aprobar lugares adicionales.');
            FamilyNotification::create(['guest_family_id' => $family->id, 'type' => 'additional_seats', 'title' => $data['decision'] === 'approved' ? 'Lugares adicionales aprobados' : 'Solicitud de lugares rechazada', 'description' => $description]);
            WeddingAudit::create(['wedding_id' => $lockedWedding->id, 'actor_id' => $request->user()->id, 'action' => 'additional_seats.resolved', 'subject_type' => AdditionalSeatRequest::class, 'subject_id' => $lockedRequest->id, 'after' => ['status' => $data['decision'], 'approved_count' => $approved]]);
        });
        return back()->with('success', 'Solicitud resuelta.');
    }
}
