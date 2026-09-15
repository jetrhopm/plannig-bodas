<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = $request->user()->notifications()
            ->latest()
            ->paginate(30)
            ->through(fn ($notification) => [
                'id' => $notification->id,
                'title' => data_get($notification->data, 'title', 'Aviso'),
                'description' => data_get($notification->data, 'description'),
                'importance' => data_get($notification->data, 'importance', 'normal'),
                'read_at' => optional($notification->read_at)->toIso8601String(),
                'created_at' => $notification->created_at->toIso8601String(),
            ]);

        return Inertia::render('Notifications/Index', ['notifications' => $notifications]);
    }

    public function markRead(Request $request, string $notification)
    {
        $item = $request->user()->notifications()->whereKey($notification)->firstOrFail();
        $item->markAsRead();

        return back();
    }

    public function markAllRead(Request $request)
    {
        $request->user()->unreadNotifications()->update(['read_at' => now()]);

        return back();
    }
}
