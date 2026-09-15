<?php

namespace App\Http\Controllers;

use App\Actions\SubmitRsvpAction;
use App\Http\Requests\SubmitRsvpRequest;
use App\Models\GuestFamily;
use Inertia\Inertia;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\SvgWriter;

class InvitationController extends Controller
{
    public function show(string $token)
    {
        $family = $this->findFamily($token);
        $family->load(['wedding.events' => fn ($query) => $query->where('is_primary', true), 'members', 'notifications' => fn ($query) => $query->latest()]);
        return Inertia::render('Invitation/Show', ['family' => $family, 'serverNow' => now()->utc()->toIso8601String()]);
    }

    public function submit(SubmitRsvpRequest $request, string $token, SubmitRsvpAction $action)
    {
        $family = $this->findFamily($token);
        $action->handle($family, $request->validated());
        return back()->with('success', 'Tu respuesta fue guardada.');
    }

    public function qr(string $token)
    {
        $this->findFamily($token);
        $qrCode = new QrCode(data: route('invitation.show', $token), size: 300, margin: 10);
        return response((new SvgWriter())->write($qrCode)->getString(), 200, ['Content-Type' => 'image/svg+xml', 'Cache-Control' => 'private, no-store']);
    }

    private function findFamily(string $token): GuestFamily
    {
        return GuestFamily::where('invite_token_hash', hash('sha256', $token))->whereNull('invite_revoked_at')->firstOrFail();
    }
}
