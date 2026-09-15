<?php

namespace App\Http\Controllers;

use App\Models\Wedding;
use App\Models\WeddingAudit;
use App\Models\WeddingProposal;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class WeddingPlanningController extends Controller
{
    use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;

    public function index(Request $request, Wedding $wedding)
    {
        $this->authorize('view', $wedding);
        abort_if(in_array($request->user()->role, ['finance', 'reception'], true), 403);
        return Inertia::render('Weddings/Planning', ['wedding' => $wedding->load(['tasks', 'agendaItems', 'proposals', 'participants', 'menuOptions', 'tables.members', 'guestFamilies.members', 'logistics']), 'canEdit' => $request->user()->can('update', $wedding), 'canApprove' => $request->user()->role === 'admin' || (bool) data_get($request->user()->weddingMemberships()->where('wedding_id', $wedding->id)->first()?->permissions, 'approve_proposals')]);
    }

    public function seating(Request $request, Wedding $wedding)
    {
        $this->authorize('update', $wedding);
        return view('weddings.seating', [
            'wedding' => $wedding->load(['tables.members.family', 'guestFamilies.members']),
            'basePath' => rtrim($request->getBaseUrl(), '/'),
            'navigation' => \App\Support\WeddingNavigation::for($request->user(), $wedding),
        ]);
    }
    public function logistics(Request $request, Wedding $wedding) { $this->authorize('update', $wedding); return Inertia::render('Weddings/Logistics', ['wedding' => $wedding->load(['logistics', 'inspirations']), 'canEdit' => $request->user()->can('update', $wedding)]); }

    public function storeTask(Request $request, Wedding $wedding) { $this->authorize('update', $wedding); $data = $request->validate(['title' => ['required','string','max:180'], 'priority' => ['required','in:low,normal,high'], 'due_date' => ['nullable','date']]); $task = $wedding->tasks()->create($data); $this->audit($wedding, $request, 'task.created', $task); return back()->with('success', 'Tarea agregada.'); }
    public function updateTask(Request $request, Wedding $wedding, \App\Models\WeddingTask $task) { $this->authorize('update', $wedding); abort_unless($task->wedding_id === $wedding->id, 404); $before = $task->only(['status', 'assigned_to']); $data = $request->validate(['status' => ['sometimes','in:pending,in_progress,completed'], 'assigned_to' => ['nullable','exists:users,id']]); $task->update($data); WeddingAudit::create(['wedding_id' => $wedding->id, 'actor_id' => $request->user()->id, 'action' => 'task.updated', 'subject_type' => get_class($task), 'subject_id' => $task->id, 'before' => $before, 'after' => $task->fresh()->only(['status','assigned_to'])]); return back()->with('success', 'Tarea actualizada.'); }
    public function storeAgenda(Request $request, Wedding $wedding) { $this->authorize('update', $wedding); $data = $request->validate(['title' => ['required','string','max:180'], 'starts_at' => ['nullable','date'], 'ends_at' => ['nullable','date','after_or_equal:starts_at'], 'location' => ['nullable','string','max:180'], 'category' => ['required','in:setup,ceremony,photos,transport,meal,cake,closing,other'], 'notes' => ['nullable','string','max:2000']]); $item = $wedding->agendaItems()->create($data); $this->audit($wedding, $request, 'agenda.created', $item); return back()->with('success', 'Momento agregado a la agenda.'); }
    public function storeProposal(Request $request, Wedding $wedding) { $this->authorize('update', $wedding); $data = $request->validate(['title' => ['required','string','max:180'], 'description' => ['nullable','string','max:5000'], 'amount' => ['nullable','numeric','min:0']]); $proposal = $wedding->proposals()->create([...$data, 'proposal_key' => Str::uuid(), 'version' => 1, 'status' => 'sent', 'created_by' => $request->user()->id]); $this->audit($wedding, $request, 'proposal.sent', $proposal); return back()->with('success', 'Propuesta enviada a la pareja.'); }
    public function reviseProposal(Request $request, Wedding $wedding, WeddingProposal $proposal) { $this->authorize('update', $wedding); abort_unless($proposal->wedding_id === $wedding->id, 404); $data = $request->validate(['title' => ['required','string','max:180'], 'description' => ['nullable','string','max:5000'], 'amount' => ['nullable','numeric','min:0']]); $next = $wedding->proposals()->create([...$data, 'proposal_key' => $proposal->proposal_key, 'version' => $proposal->version + 1, 'status' => 'sent', 'created_by' => $request->user()->id]); $this->audit($wedding, $request, 'proposal.revised', $next); return back()->with('success', 'Nueva versión enviada; requiere una aprobación nueva.'); }
    public function decideProposal(Request $request, Wedding $wedding, WeddingProposal $proposal) { $this->authorize('view', $wedding); abort_unless($proposal->wedding_id === $wedding->id, 404); $membership = $request->user()->weddingMemberships()->where('wedding_id', $wedding->id)->first(); abort_unless($request->user()->role === 'admin' || data_get($membership?->permissions, 'approve_proposals'), 403); $data = $request->validate(['decision' => ['required','in:approved,changes_requested'], 'couple_comment' => ['nullable','string','max:5000']]); abort_unless($proposal->status === 'sent', 422); $proposal->update(['status' => $data['decision'], 'couple_comment' => $data['couple_comment'] ?? null, 'decided_by' => $request->user()->id, 'decided_at' => now()]); $this->audit($wedding, $request, 'proposal.decided', $proposal); return back()->with('success', $data['decision'] === 'approved' ? 'Propuesta aprobada.' : 'Cambios solicitados a la empresa.'); }
    public function storeParticipant(Request $request, Wedding $wedding) { $this->authorize('update', $wedding); $item = $wedding->participants()->create($request->validate(['name' => ['required','string','max:180'], 'role' => ['required','string','max:100'], 'phone' => ['nullable','string','max:40'], 'schedule_note' => ['nullable','string','max:500']])); $this->audit($wedding, $request, 'participant.created', $item); return back()->with('success', 'Participante agregado.'); }
    public function storeMenu(Request $request, Wedding $wedding) { $this->authorize('update', $wedding); $item = $wedding->menuOptions()->create($request->validate(['name' => ['required','string','max:180'], 'description' => ['nullable','string','max:1000']])); $this->audit($wedding, $request, 'menu_option.created', $item); return back()->with('success', 'Opción de menú agregada.'); }
    public function storeTable(Request $request, Wedding $wedding) { $this->authorize('update', $wedding); $item = $wedding->tables()->create($request->validate(['label' => ['required','string','max:100'], 'capacity' => ['required','integer','min:1','max:1000']])); $this->audit($wedding, $request, 'table.created', $item); return back()->with('success', 'Mesa agregada.'); }
    public function storeLogistic(Request $request, Wedding $wedding) { $this->authorize('update', $wedding); $item = $wedding->logistics()->create($request->validate(['type' => ['required','in:vendor,hotel,transport,venue,decor,cake'], 'name' => ['required','string','max:180'], 'status' => ['required','in:recommended,managed,reserved,confirmed'], 'contact' => ['nullable','string','max:180'], 'notes' => ['nullable','string','max:2000']])); $this->audit($wedding, $request, 'logistic.created', $item); return back()->with('success', 'Recurso logístico agregado.'); }
    public function storeInspiration(Request $request, Wedding $wedding) { $this->authorize('update', $wedding); $item = $wedding->inspirations()->create($request->validate(['title' => ['required','string','max:180'], 'category' => ['required','string','max:100'], 'note' => ['nullable','string','max:2000'], 'reference_url' => ['nullable','url','max:2000']])); $this->audit($wedding, $request, 'inspiration.created', $item); return back()->with('success', 'Referencia agregada al tablero.'); }
    public function assignTable(Request $request, Wedding $wedding, \App\Models\GuestFamilyMember $member) { $this->authorize('update', $wedding); abort_unless($member->family->wedding_id === $wedding->id, 404); $data = $request->validate(['wedding_table_id' => ['nullable','exists:wedding_tables,id']]); if ($data['wedding_table_id']) { $table = $wedding->tables()->findOrFail($data['wedding_table_id']); abort_if($table->members()->where('guest_family_members.id', '!=', $member->id)->count() >= $table->capacity, 422, 'La mesa ya alcanzó su capacidad.'); } $member->update($data); $this->audit($wedding, $request, 'table.assigned', $member); return back()->with('success', 'Mesa asignada.'); }
    private function audit(Wedding $wedding, Request $request, string $action, mixed $subject): void { WeddingAudit::create(['wedding_id' => $wedding->id, 'actor_id' => $request->user()->id, 'action' => $action, 'subject_type' => get_class($subject), 'subject_id' => $subject->id, 'after' => $subject->toArray()]); }
}
