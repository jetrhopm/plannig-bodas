<?php

namespace App\Actions;

use App\Models\FamilyNotification;
use App\Models\GuestFamily;
use App\Models\RsvpResponse;
use App\Models\Wedding;
use App\Models\WeddingAudit;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SubmitRsvpAction
{
    /** @param array<string, mixed> $data */
    public function handle(GuestFamily $family, array $data): RsvpResponse
    {
        return DB::transaction(function () use ($family, $data) {
            $lockedFamily = GuestFamily::query()->lockForUpdate()->findOrFail($family->id);
            $wedding = Wedding::query()->lockForUpdate()->findOrFail($lockedFamily->wedding_id);
            if ($existing = $lockedFamily->responses()->where('idempotency_key', $data['idempotency_key'])->first()) return $existing;
            if ($lockedFamily->rsvp_status !== 'pending') throw ValidationException::withMessages(['response' => 'Esta invitación ya fue respondida. Para modificarla deberás solicitar disponibilidad a la empresa.']);

            $attending = $data['response'] === 'confirmed' ? (int) $data['attending_count'] : 0;
            if ($attending > $lockedFamily->current_allocation) throw ValidationException::withMessages(['attending_count' => 'La cantidad excede los pases vigentes.']);
            $before = $lockedFamily->current_allocation;
            $after = $attending;
            $lockedFamily->update(['rsvp_status' => $data['response'], 'attending_count' => $attending, 'current_allocation' => $after]);
            foreach ($data['members'] ?? [] as $position => $member) $lockedFamily->members()->updateOrCreate(['position' => $position + 1], ['display_name' => $member['display_name'] ?? null, 'attending' => $member['attending'] ?? null, 'dietary_restrictions' => $member['dietary_restrictions'] ?? null, 'accessibility_needs' => $member['accessibility_needs'] ?? null]);
            $response = $lockedFamily->responses()->create(['idempotency_key' => $data['idempotency_key'], 'response' => $data['response'], 'attending_count' => $attending, 'allocation_before' => $before, 'allocation_after' => $after, 'member_details' => $data['members'] ?? [], 'responded_at' => now()]);
            $title = $data['response'] === 'declined' ? 'Invitación rechazada' : 'Asistencia confirmada';
            $description = $data['response'] === 'declined' ? 'Se liberaron todos los pases de la familia.' : "Confirmaron {$attending} de {$before} pases.";
            FamilyNotification::create(['guest_family_id' => $lockedFamily->id, 'type' => 'rsvp', 'title' => $title, 'description' => $description]);
            WeddingAudit::create(['wedding_id' => $wedding->id, 'action' => 'family.rsvp_submitted', 'subject_type' => GuestFamily::class, 'subject_id' => $lockedFamily->id, 'before' => ['allocation' => $before, 'status' => 'pending'], 'after' => ['allocation' => $after, 'status' => $data['response'], 'attending_count' => $attending]]);
            DB::afterCommit(function () use ($wedding, $lockedFamily, $title, $description) {
                $wedding->memberships()->with('user')->get()->each(fn ($membership) => $membership->user->notifications()->create(['id' => (string) \Illuminate\Support\Str::uuid(), 'type' => 'rsvp', 'data' => ['title' => $title, 'description' => "{$lockedFamily->label}: {$description}", 'importance' => 'normal']]));
            });
            return $response;
        });
    }
}
