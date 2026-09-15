<?php

namespace App\Http\Controllers;

use App\Models\AdditionalSeatRequest;
use App\Models\GuestFamily;
use App\Models\WeddingEvent;
use App\Models\WeddingFinanceItem;
use App\Models\WeddingProposal;
use App\Models\WeddingTask;
use App\Models\Wedding;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $user = $request->user();
        $query = Wedding::query()->with(['coordinator:id,name', 'events' => fn ($q) => $q->where('is_primary', true)]);
        if ($user->role !== 'admin') {
            $query->where(function ($query) use ($user) {
                $query->where('coordinator_id', $user->id)
                    ->orWhereHas('memberships', fn ($membership) => $membership->where('user_id', $user->id));
            });
        }
        $weddings = $query->latest()->get();
        $weddingIds = $weddings->modelKeys();
        $summary = [
            'nextEvents' => WeddingEvent::query()->whereIn('wedding_id', $weddingIds)->whereNotNull('event_date')->orderBy('event_date')->limit(5)->get(['id', 'wedding_id', 'name', 'event_date', 'event_time', 'venue']),
            'tasks' => [],
            'seatRequests' => [],
            'financeItems' => [],
            'receptionFamilies' => [],
            'proposals' => [],
        ];

        if (in_array($user->role, ['admin', 'coordinator'], true)) {
            $summary['tasks'] = WeddingTask::query()->whereIn('wedding_id', $weddingIds)->where('status', '!=', 'completed')->orderByRaw('due_date is null, due_date')->limit(6)->get(['id', 'wedding_id', 'title', 'priority', 'due_date', 'status']);
            $summary['seatRequests'] = AdditionalSeatRequest::query()->where('status', 'pending')->whereHas('family', fn ($families) => $families->whereIn('wedding_id', $weddingIds))->with('family:id,wedding_id,label')->latest()->limit(6)->get(['id', 'guest_family_id', 'requested_count', 'request_note', 'status']);
        }

        if (in_array($user->role, ['admin', 'finance'], true)) {
            $summary['financeItems'] = WeddingFinanceItem::query()->whereIn('wedding_id', $weddingIds)->where('status', '!=', 'paid')->orderByRaw('due_date is null, due_date')->limit(6)->get(['id', 'wedding_id', 'description', 'amount', 'due_date', 'status']);
        }

        if (in_array($user->role, ['admin', 'coordinator', 'reception'], true)) {
            $summary['receptionFamilies'] = GuestFamily::query()->whereIn('wedding_id', $weddingIds)->where('rsvp_status', 'confirmed')->withSum('accessEntries as entered_count', 'count')->orderBy('wedding_id')->limit(8)->get(['id', 'wedding_id', 'label', 'attending_count']);
        }

        if ($user->role === 'couple') {
            $summary['proposals'] = WeddingProposal::query()->whereIn('wedding_id', $weddingIds)->where('status', 'sent')->latest()->limit(6)->get(['id', 'wedding_id', 'title', 'amount', 'version', 'status']);
        }

        return view('dashboard', [
            'weddings' => $weddings,
            'unreadNotifications' => $user->unreadNotifications()->count(),
            'role' => $user->role,
            'canCreateWedding' => $user->can('create', Wedding::class),
            'roleSummary' => $summary,
            'basePath' => rtrim($request->getBaseUrl(), '/'),
        ]);
    }
}
