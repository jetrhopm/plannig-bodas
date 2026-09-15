<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateWeddingInterviewRequest;
use App\Models\Wedding;
use App\Models\WeddingAudit;
use Illuminate\Support\Arr;
use Inertia\Inertia;

class WeddingInterviewController extends Controller
{
    use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;

    public function edit(Wedding $wedding)
    {
        $this->authorize('update', $wedding);
        $interview = $wedding->interview()->firstOrCreate([], ['answers' => [], 'status' => 'draft']);

        return Inertia::render('Weddings/Interview', ['wedding' => $wedding, 'interview' => $interview]);
    }

    public function update(UpdateWeddingInterviewRequest $request, Wedding $wedding)
    {
        $validated = $request->validated();
        $interview = $wedding->interview()->firstOrCreate([], ['answers' => [], 'status' => 'draft']);
        $before = $interview->answers ?? [];
        $answers = array_merge($before, Arr::except($validated, ['current_step', 'support_type', 'authorized_capacity', 'timezone']));
        $interview->update(['current_step' => $validated['current_step'], 'answers' => $answers, 'last_saved_at' => now()]);
        $weddingBefore = $wedding->only(['support_type', 'authorized_capacity', 'timezone']);
        $wedding->update(Arr::only($validated, ['support_type', 'authorized_capacity', 'timezone']));
        WeddingAudit::create(['wedding_id' => $wedding->id, 'actor_id' => $request->user()->id, 'action' => 'interview.saved', 'subject_type' => get_class($interview), 'subject_id' => $interview->id, 'before' => ['answers' => $before, 'wedding' => $weddingBefore], 'after' => ['answers' => $answers, 'wedding' => $wedding->fresh()->only(array_keys($weddingBefore))]]);

        return back()->with('success', 'Borrador guardado. Puedes retomarlo cuando quieras.');
    }
}
