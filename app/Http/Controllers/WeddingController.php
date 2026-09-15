<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateWeddingEventRequest;
use App\Models\Wedding;
use App\Models\WeddingAudit;
use App\Models\WeddingEvent;
use App\Models\WeddingMembership;
use App\Models\WeddingResponsibility;
use App\Models\WeddingService;
use App\Models\WeddingServiceTemplate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;

class WeddingController extends Controller
{
    use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;

    public function create(Request $request)
    {
        $this->authorize('create', Wedding::class);

        return Inertia::render('Weddings/Create', [
            'coordinators' => \App\Models\User::query()
                ->whereIn('role', ['admin', 'coordinator'])
                ->orderBy('name')
                ->get(['id', 'name', 'email']),
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Wedding::class);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:180'],
            'status' => ['required', 'in:consultation,contracted,preparation'],
            'support_type' => ['required', 'in:full,shared,advisory'],
            'authorized_capacity' => ['nullable', 'integer', 'min:1', 'max:100000'],
            'timezone' => ['required', 'timezone'],
            'coordinator_id' => ['nullable', 'exists:users,id'],
        ]);

        $wedding = DB::transaction(function () use ($data, $request) {
            $baseSlug = Str::slug($data['name']) ?: 'boda';
            $slug = $baseSlug;
            $suffix = 2;
            while (Wedding::query()->where('slug', $slug)->exists()) {
                $slug = $baseSlug.'-'.$suffix++;
            }
            $wedding = Wedding::create([...$data, 'slug' => $slug]);
            $wedding->interview()->create(['current_step' => 1, 'status' => 'draft', 'answers' => [], 'last_saved_at' => now()]);
            WeddingAudit::create(['wedding_id' => $wedding->id, 'actor_id' => $request->user()->id, 'action' => 'wedding.created', 'subject_type' => Wedding::class, 'subject_id' => $wedding->id, 'after' => $wedding->toArray()]);

            return $wedding;
        });

        return redirect()->route('weddings.workspace', $wedding)->with('success', 'Nueva boda creada. Continúa con la entrevista y los eventos.');
    }

    public function show(Request $request, Wedding $wedding)
    {
        $this->authorize('view', $wedding);
        abort_if(in_array($request->user()->role, ['finance', 'reception'], true), 403);
        $wedding->load(['events', 'services', 'responsibilities', 'memberships.user:id,name,email,role', 'interview', 'capacityExpansionRequests' => fn ($query) => $query->latest()]);
        $membership = $request->user()->weddingMemberships()->where('wedding_id', $wedding->id)->first();

        return Inertia::render('Weddings/Show', [
            'wedding' => $wedding,
            'serverNow' => now()->utc()->toIso8601String(),
            'canEdit' => $request->user()->can('update', $wedding),
            'canRequestCapacity' => $request->user()->can('view', $wedding),
            'canRespondCapacity' => $request->user()->role === 'admin' || (bool) data_get($membership?->permissions, 'approve_proposals'),
            'isAdmin' => $request->user()->role === 'admin',
            'canReceive' => $request->user()->role === 'admin' || (bool) data_get($membership?->permissions, 'scan_passes') || (bool) data_get($membership?->permissions, 'edit_wedding'),
            'serviceTemplates' => WeddingServiceTemplate::query()->where('is_active', true)->orderBy('name')->get(['id', 'name', 'category', 'default_owner']),
        ]);
    }

    public function update(Request $request, Wedding $wedding)
    {
        $this->authorize('update', $wedding);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:180'],
            'status' => ['required', 'in:consultation,contracted,preparation'],
            'support_type' => ['required', 'in:full,shared,advisory'],
            'authorized_capacity' => ['nullable', 'integer', 'min:1', 'max:100000'],
            'timezone' => ['required', 'timezone'],
        ]);
        $before = $wedding->only(array_keys($data));
        $wedding->update($data);
        WeddingAudit::create(['wedding_id' => $wedding->id, 'actor_id' => $request->user()->id, 'action' => 'wedding.updated', 'subject_type' => Wedding::class, 'subject_id' => $wedding->id, 'before' => $before, 'after' => $wedding->fresh()->only(array_keys($data))]);
        return back()->with('success', 'Expediente actualizado.');
    }

    public function workspace(Request $request, Wedding $wedding)
    {
        $this->authorize('view', $wedding);
        $membership = $request->user()->weddingMemberships()->where('wedding_id', $wedding->id)->first();
        $canEdit = $request->user()->can('update', $wedding);
        $canFinance = $request->user()->role === 'admin' || (bool) data_get($membership?->permissions, 'view_finance');
        $canReceive = $request->user()->role === 'admin' || $wedding->coordinator_id === $request->user()->id || (bool) data_get($membership?->permissions, 'scan_passes') || (bool) data_get($membership?->permissions, 'edit_wedding');

        return view('weddings.workspace', [
            'wedding' => $wedding->load(['events' => fn ($query) => $query->orderByDesc('is_primary')]),
            'canEdit' => $canEdit,
            'canFinance' => $canFinance,
            'canReceive' => $canReceive,
            'canPlan' => ! in_array($request->user()->role, ['finance', 'reception'], true),
            'canViewProfile' => ! in_array($request->user()->role, ['finance', 'reception'], true),
            'basePath' => rtrim($request->getBaseUrl(), '/'),
        ]);
    }

    public function updateEvent(UpdateWeddingEventRequest $request, Wedding $wedding, WeddingEvent $event)
    {
        abort_unless($event->wedding_id === $wedding->id, 404);
        $before = $event->only(['name', 'type', 'event_date', 'event_time', 'starts_at', 'venue', 'address', 'is_primary']);
        $data = $request->validated();
        $data['starts_at'] = filled($data['event_date']) && filled($data['event_time'])
            ? Carbon::createFromFormat('Y-m-d H:i', $data['event_date'].' '.$data['event_time'], $wedding->timezone)->utc()
            : null;

        if (($data['is_primary'] ?? false) === true) {
            $wedding->events()->whereKeyNot($event->id)->update(['is_primary' => false]);
        }
        $event->update($data);
        WeddingAudit::create(['wedding_id' => $wedding->id, 'actor_id' => $request->user()->id, 'action' => 'event.updated', 'subject_type' => WeddingEvent::class, 'subject_id' => $event->id, 'before' => $before, 'after' => $event->fresh()->only(array_keys($before))]);

        return back()->with('success', 'Evento actualizado.');
    }

    public function destroyEvent(Request $request, Wedding $wedding, WeddingEvent $event)
    {
        $this->authorize('update', $wedding);
        abort_unless($event->wedding_id === $wedding->id, 404);
        WeddingAudit::create(['wedding_id' => $wedding->id, 'actor_id' => $request->user()->id, 'action' => 'event.deleted', 'subject_type' => WeddingEvent::class, 'subject_id' => $event->id, 'before' => $event->toArray()]);
        $event->delete();
        return back()->with('success', 'Evento eliminado.');
    }

    public function createEvent(Wedding $wedding)
    {
        $this->authorize('update', $wedding);
        return Inertia::render('Weddings/EventCreate', ['wedding' => $wedding]);
    }

    public function storeEvent(Request $request, Wedding $wedding)
    {
        $this->authorize('update', $wedding);
        $data = $request->validate(['name' => ['required','string','max:180'], 'type' => ['required','in:reception,ceremony,civil,after_party,other'], 'event_date' => ['nullable','date'], 'event_time' => ['nullable','date_format:H:i'], 'venue' => ['nullable','string','max:180'], 'address' => ['nullable','string','max:500'], 'is_primary' => ['boolean']]);
        $data['starts_at'] = filled($data['event_date'] ?? null) && filled($data['event_time'] ?? null) ? Carbon::createFromFormat('Y-m-d H:i', $data['event_date'].' '.$data['event_time'], $wedding->timezone)->utc() : null;
        if ($data['is_primary'] ?? false) $wedding->events()->update(['is_primary' => false]);
        $event = $wedding->events()->create($data);
        WeddingAudit::create(['wedding_id' => $wedding->id, 'actor_id' => $request->user()->id, 'action' => 'event.created', 'subject_type' => WeddingEvent::class, 'subject_id' => $event->id, 'after' => $event->toArray()]);
        return redirect()->route('weddings.show', $wedding)->with('success', 'Evento creado.');
    }

    public function updateMembershipPermissions(Request $request, Wedding $wedding, WeddingMembership $membership)
    {
        $this->authorize('update', $wedding);
        abort_unless($membership->wedding_id === $wedding->id, 404);
        $data = $request->validate(['permissions' => ['required', 'array'], 'permissions.*' => ['boolean']]);
        $before = $membership->permissions;
        $membership->update(['permissions' => $data['permissions']]);
        WeddingAudit::create(['wedding_id' => $wedding->id, 'actor_id' => $request->user()->id, 'action' => 'membership.permissions_updated', 'subject_type' => WeddingMembership::class, 'subject_id' => $membership->id, 'before' => ['permissions' => $before], 'after' => ['permissions' => $data['permissions']]]);

        return back()->with('success', 'Permisos efectivos actualizados.');
    }

    public function storeService(Request $request, Wedding $wedding)
    {
        $this->authorize('update', $wedding);
        $data = $request->validate(['wedding_service_template_id' => ['nullable', 'exists:wedding_service_templates,id'], 'name' => ['required', 'string', 'max:160'], 'owner' => ['required', 'in:company,couple,shared']]);
        $template = isset($data['wedding_service_template_id']) ? WeddingServiceTemplate::find($data['wedding_service_template_id']) : null;
        $service = $wedding->services()->create(['wedding_service_template_id' => $template?->id, 'name' => $data['name'], 'category' => $template?->category, 'owner' => $data['owner'], 'status' => 'included']);
        WeddingAudit::create(['wedding_id' => $wedding->id, 'actor_id' => $request->user()->id, 'action' => 'service.created', 'subject_type' => WeddingService::class, 'subject_id' => $service->id, 'after' => $service->toArray()]);
        return back()->with('success', 'Servicio agregado a la configuración.');
    }

    public function updateService(Request $request, Wedding $wedding, WeddingService $service)
    {
        $this->authorize('update', $wedding);
        abort_unless($service->wedding_id === $wedding->id, 404);
        $before = $service->toArray();
        $service->update($request->validate(['name' => ['required', 'string', 'max:160'], 'owner' => ['required', 'in:company,couple,shared'], 'status' => ['required', 'in:included,pending,confirmed,cancelled'], 'estimated_cost' => ['nullable', 'numeric', 'min:0'], 'details' => ['nullable', 'array']]));
        WeddingAudit::create(['wedding_id' => $wedding->id, 'actor_id' => $request->user()->id, 'action' => 'service.updated', 'subject_type' => WeddingService::class, 'subject_id' => $service->id, 'before' => $before, 'after' => $service->fresh()->toArray()]);
        return back()->with('success', 'Servicio actualizado.');
    }

    public function destroyService(Request $request, Wedding $wedding, WeddingService $service)
    {
        $this->authorize('update', $wedding);
        abort_unless($service->wedding_id === $wedding->id, 404);
        WeddingAudit::create(['wedding_id' => $wedding->id, 'actor_id' => $request->user()->id, 'action' => 'service.deleted', 'subject_type' => WeddingService::class, 'subject_id' => $service->id, 'before' => $service->toArray()]);
        $service->delete();
        return back()->with('success', 'Servicio eliminado.');
    }

    public function storeResponsibility(Request $request, Wedding $wedding)
    {
        $this->authorize('update', $wedding);
        $data = $request->validate(['label' => ['required', 'string', 'max:180'], 'owner' => ['required', 'in:company,couple,shared'], 'due_date' => ['nullable', 'date'], 'notes' => ['nullable', 'string', 'max:2000']]);
        $responsibility = $wedding->responsibilities()->create($data);
        WeddingAudit::create(['wedding_id' => $wedding->id, 'actor_id' => $request->user()->id, 'action' => 'responsibility.created', 'subject_type' => WeddingResponsibility::class, 'subject_id' => $responsibility->id, 'after' => $responsibility->toArray()]);
        return back()->with('success', 'Responsabilidad agregada.');
    }

    public function updateResponsibility(Request $request, Wedding $wedding, WeddingResponsibility $responsibility)
    {
        $this->authorize('update', $wedding);
        abort_unless($responsibility->wedding_id === $wedding->id, 404);
        $before = $responsibility->toArray();
        $responsibility->update($request->validate(['label' => ['required', 'string', 'max:180'], 'owner' => ['required', 'in:company,couple,shared'], 'status' => ['required', 'in:pending,in_progress,completed,cancelled'], 'due_date' => ['nullable', 'date'], 'notes' => ['nullable', 'string', 'max:2000']]));
        WeddingAudit::create(['wedding_id' => $wedding->id, 'actor_id' => $request->user()->id, 'action' => 'responsibility.updated', 'subject_type' => WeddingResponsibility::class, 'subject_id' => $responsibility->id, 'before' => $before, 'after' => $responsibility->fresh()->toArray()]);
        return back()->with('success', 'Responsabilidad actualizada.');
    }

    public function destroyResponsibility(Request $request, Wedding $wedding, WeddingResponsibility $responsibility)
    {
        $this->authorize('update', $wedding);
        abort_unless($responsibility->wedding_id === $wedding->id, 404);
        WeddingAudit::create(['wedding_id' => $wedding->id, 'actor_id' => $request->user()->id, 'action' => 'responsibility.deleted', 'subject_type' => WeddingResponsibility::class, 'subject_id' => $responsibility->id, 'before' => $responsibility->toArray()]);
        $responsibility->delete();
        return back()->with('success', 'Responsabilidad eliminada.');
    }

    public function activateConfiguration(Request $request, Wedding $wedding)
    {
        $this->authorize('update', $wedding);
        $interview = $wedding->interview;
        abort_unless($interview, 422, 'Primero guarda la entrevista inicial.');
        if ($interview->status === 'active') return back()->with('success', 'La configuración ya estaba activa.');
        DB::transaction(function () use ($wedding, $interview, $request) {
            $interview->update(['status' => 'active']);
            WeddingAudit::create(['wedding_id' => $wedding->id, 'actor_id' => $request->user()->id, 'action' => 'configuration.activated', 'subject_type' => get_class($interview), 'subject_id' => $interview->id, 'after' => ['status' => 'active']]);
            DB::afterCommit(function () use ($wedding) {
                $wedding->memberships()->where('relationship', 'partner')->with('user')->get()->each(fn ($membership) => $membership->user->notifications()->create(['id' => (string) Str::uuid(), 'type' => 'configuration', 'data' => ['title' => 'Configuración de boda activada', 'description' => "La configuración de {$wedding->name} está lista para seguimiento.", 'importance' => 'normal']]));
            });
        });
        return back()->with('success', 'Configuración activada y pareja notificada.');
    }
}
