<?php

namespace App\Http\Controllers;

use App\Http\Requests\RecordGuestAccessRequest;
use App\Models\GuestAccessEntry;
use App\Models\GuestFamily;
use App\Models\Wedding;
use App\Models\WeddingAudit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ReceptionController extends Controller
{
    public function index(Request $request, Wedding $wedding)
    {
        $this->authorizeReception($request, $wedding);
        $code = (string) $request->query('code', '');
        $token = Str::afterLast(rtrim($code, '/'), '/');
        $selected = $code === '' ? null : $wedding->guestFamilies()->where('invite_token_hash', hash('sha256', $token))->with(['accessEntries' => fn ($query) => $query->latest(), 'accessEntries.operator:id,name'])->first();
        return Inertia::render('Reception/Index', ['wedding' => $wedding, 'families' => $wedding->guestFamilies()->withSum('accessEntries as entered_count', 'count')->orderBy('label')->get(), 'selected' => $selected]);
    }

    public function correct(Request $request, Wedding $wedding, GuestAccessEntry $entry)
    {
        $this->authorizeReception($request, $wedding);
        abort_unless($request->user()->role === 'admin', 403);
        $data = $request->validate(['reason' => ['required', 'string', 'max:1000']]);
        DB::transaction(function () use ($entry, $wedding, $request, $data) {
            $item = GuestAccessEntry::query()->lockForUpdate()->findOrFail($entry->id);
            abort_unless($item->family->wedding_id === $wedding->id, 404);
            WeddingAudit::create(['wedding_id' => $wedding->id, 'actor_id' => $request->user()->id, 'action' => 'reception.entry_corrected', 'subject_type' => GuestAccessEntry::class, 'subject_id' => $item->id, 'before' => ['count' => $item->count, 'entered_at' => $item->entered_at], 'after' => ['reason' => $data['reason']]]);
            $item->delete();
        });
        return back()->with('success', 'Movimiento corregido y auditado.');
    }

    public function record(RecordGuestAccessRequest $request, Wedding $wedding, GuestFamily $family)
    {
        $this->authorizeReception($request, $wedding);
        abort_unless($family->wedding_id === $wedding->id, 404);
        $data = $request->validated();
        DB::transaction(function () use ($family, $data, $request, $wedding) {
            $locked = GuestFamily::query()->lockForUpdate()->findOrFail($family->id);
            $existing = GuestAccessEntry::where('guest_family_id', $locked->id)->where('idempotency_key', $data['idempotency_key'])->first();
            if ($existing) return;
            $confirmed = $locked->rsvp_status === 'confirmed' ? (int) $locked->attending_count : 0;
            $entered = (int) GuestAccessEntry::where('guest_family_id', $locked->id)->lockForUpdate()->sum('count');
            if ($data['count'] > $confirmed - $entered) throw ValidationException::withMessages(['count' => 'La cantidad excede los ingresos confirmados disponibles.']);
            GuestAccessEntry::create(['guest_family_id' => $locked->id, 'user_id' => $request->user()->id, 'count' => $data['count'], 'idempotency_key' => $data['idempotency_key'], 'entered_at' => now()]);
            WeddingAudit::create(['wedding_id' => $wedding->id, 'actor_id' => $request->user()->id, 'action' => 'reception.entry_recorded', 'subject_type' => GuestFamily::class, 'subject_id' => $locked->id, 'after' => ['count' => $data['count'], 'entered_after' => $entered + $data['count']]]);
        });
        return back()->with('success', 'Ingreso registrado.');
    }

    private function authorizeReception(Request $request, Wedding $wedding): void
    {
        $user = $request->user();
        $membership = $user->weddingMemberships()->where('wedding_id', $wedding->id)->first();
        abort_unless($user->role === 'admin' || ($membership && (data_get($membership->permissions, 'scan_passes') || data_get($membership->permissions, 'edit_wedding'))), 403);
    }
}
