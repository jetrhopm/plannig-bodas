<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGuestFamilyRequest;
use App\Models\GuestFamily;
use App\Models\Wedding;
use App\Models\WeddingAudit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class WeddingFamilyController extends Controller
{
    use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;

    public function index(Wedding $wedding)
    {
        $this->authorize('update', $wedding);
        $families = $wedding->guestFamilies()->with(['additionalSeatRequests' => fn ($query) => $query->latest()])->withCount('members')->orderBy('label')->get();
        $assigned = $families->sum('current_allocation');
        return Inertia::render('Weddings/Families', ['wedding' => $wedding, 'families' => $families, 'assignedSeats' => $assigned, 'availableSeats' => $wedding->authorized_capacity === null ? null : max(0, $wedding->authorized_capacity - $assigned), 'canEdit' => request()->user()->can('update', $wedding)]);
    }

    public function store(StoreGuestFamilyRequest $request, Wedding $wedding)
    {
        $data = $request->validated();
        $token = Str::random(48);
        DB::transaction(function () use ($wedding, $data, $request, $token) {
            $lockedWedding = Wedding::query()->lockForUpdate()->findOrFail($wedding->id);
            $assigned = GuestFamily::where('wedding_id', $lockedWedding->id)->lockForUpdate()->sum('current_allocation');
            if ($lockedWedding->authorized_capacity !== null && $assigned + $data['allocation'] > $lockedWedding->authorized_capacity) throw ValidationException::withMessages(['allocation' => 'La asignación excede el cupo autorizado disponible.']);
            $family = $lockedWedding->guestFamilies()->create(['label' => $data['label'], 'responsible_name' => $data['responsible_name'] ?? null, 'responsible_email' => $data['responsible_email'] ?? null, 'responsible_phone' => $data['responsible_phone'] ?? null, 'original_allocation' => $data['allocation'], 'current_allocation' => $data['allocation'], 'invite_token_hash' => hash('sha256', $token)]);
            WeddingAudit::create(['wedding_id' => $lockedWedding->id, 'actor_id' => $request->user()->id, 'action' => 'family.created', 'subject_type' => GuestFamily::class, 'subject_id' => $family->id, 'after' => ['allocation' => $family->current_allocation, 'label' => $family->label]]);
        });
        return back()->with('success', 'Familia creada. Copia el enlace privado ahora: '.route('invitation.show', $token));
    }
}
