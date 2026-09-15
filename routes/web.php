<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\WeddingController;
use App\Http\Controllers\WeddingInterviewController;
use App\Http\Controllers\WeddingFamilyController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\AdditionalSeatRequestController;
use App\Http\Controllers\CapacityExpansionRequestController;
use App\Http\Controllers\ReceptionController;
use App\Http\Controllers\WeddingPlanningController;
use App\Http\Controllers\WeddingFinanceController;
use App\Http\Controllers\FinanceAttachmentController;
use App\Http\Controllers\GiftController;
use App\Http\Controllers\CommunicationPreferenceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function (Request $request) {
    $basePath = rtrim($request->getBaseUrl(), '/');

    return view('landing', [
        'basePath' => $basePath,
        'portalUrl' => $basePath.($request->user() ? '/dashboard' : '/login'),
    ]);
});

Route::get('/invitacion/{token}', [InvitationController::class, 'show'])->name('invitation.show');
Route::post('/invitacion/{token}/rsvp', [InvitationController::class, 'submit'])->name('invitation.rsvp');
Route::post('/invitacion/{token}/lugares-adicionales', [AdditionalSeatRequestController::class, 'store'])->name('invitation.additional-seats.store');
Route::get('/invitacion/{token}/qr.svg', [InvitationController::class, 'qr'])->name('invitation.qr');
Route::get('/regalos/{wedding}', [GiftController::class, 'index'])->name('gifts.index');
Route::post('/regalos/{wedding}/{gift}/reservar', [GiftController::class, 'reserve'])->name('gifts.reserve');

Route::get('/dashboard', DashboardController::class)->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/bodas/nueva', [WeddingController::class, 'create'])->name('weddings.create');
    Route::post('/bodas', [WeddingController::class, 'store'])->name('weddings.store');
    Route::get('/bodas/{wedding}', [WeddingController::class, 'show'])->name('weddings.show');
    Route::patch('/bodas/{wedding}', [WeddingController::class, 'update'])->name('weddings.update');
    Route::get('/bodas/{wedding}/centro', [WeddingController::class, 'workspace'])->name('weddings.workspace');
    Route::put('/bodas/{wedding}/eventos/{event}', [WeddingController::class, 'updateEvent'])->name('weddings.events.update');
    Route::delete('/bodas/{wedding}/eventos/{event}', [WeddingController::class, 'destroyEvent'])->name('weddings.events.destroy');
    Route::get('/bodas/{wedding}/eventos/nuevo', [WeddingController::class, 'createEvent'])->name('weddings.events.create');
    Route::post('/bodas/{wedding}/eventos', [WeddingController::class, 'storeEvent'])->name('weddings.events.store');
    Route::patch('/bodas/{wedding}/miembros/{membership}/permisos', [WeddingController::class, 'updateMembershipPermissions'])->name('weddings.memberships.permissions');
    Route::post('/bodas/{wedding}/servicios', [WeddingController::class, 'storeService'])->name('weddings.services.store');
    Route::patch('/bodas/{wedding}/servicios/{service}', [WeddingController::class, 'updateService'])->name('weddings.services.update');
    Route::delete('/bodas/{wedding}/servicios/{service}', [WeddingController::class, 'destroyService'])->name('weddings.services.destroy');
    Route::post('/bodas/{wedding}/responsabilidades', [WeddingController::class, 'storeResponsibility'])->name('weddings.responsibilities.store');
    Route::patch('/bodas/{wedding}/responsabilidades/{responsibility}', [WeddingController::class, 'updateResponsibility'])->name('weddings.responsibilities.update');
    Route::delete('/bodas/{wedding}/responsabilidades/{responsibility}', [WeddingController::class, 'destroyResponsibility'])->name('weddings.responsibilities.destroy');
    Route::post('/bodas/{wedding}/configuracion/activar', [WeddingController::class, 'activateConfiguration'])->name('weddings.configuration.activate');
    Route::get('/bodas/{wedding}/familias', [WeddingFamilyController::class, 'index'])->name('weddings.families.index');
    Route::get('/bodas/{wedding}/recepcion', [ReceptionController::class, 'index'])->name('weddings.reception.index');
    Route::post('/bodas/{wedding}/recepcion/{family}/ingresos', [ReceptionController::class, 'record'])->name('weddings.reception.record');
    Route::delete('/bodas/{wedding}/recepcion/ingresos/{entry}', [ReceptionController::class, 'correct'])->name('weddings.reception.correct');
    Route::post('/bodas/{wedding}/familias', [WeddingFamilyController::class, 'store'])->name('weddings.families.store');
    Route::patch('/bodas/{wedding}/solicitudes-lugares/{seatRequest}', [AdditionalSeatRequestController::class, 'resolve'])->name('weddings.additional-seats.resolve');
    Route::post('/bodas/{wedding}/ampliaciones-cupo', [CapacityExpansionRequestController::class, 'store'])->name('weddings.capacity-expansions.store');
    Route::patch('/bodas/{wedding}/ampliaciones-cupo/{capacityRequest}/propuesta', [CapacityExpansionRequestController::class, 'propose'])->name('weddings.capacity-expansions.propose');
    Route::patch('/bodas/{wedding}/ampliaciones-cupo/{capacityRequest}/respuesta', [CapacityExpansionRequestController::class, 'respond'])->name('weddings.capacity-expansions.respond');
    Route::patch('/bodas/{wedding}/ampliaciones-cupo/{capacityRequest}', [CapacityExpansionRequestController::class, 'resolve'])->name('weddings.capacity-expansions.resolve');
    Route::get('/bodas/{wedding}/entrevista', [WeddingInterviewController::class, 'edit'])->name('weddings.interview.edit');
    Route::get('/bodas/{wedding}/planeacion', [WeddingPlanningController::class, 'index'])->name('weddings.planning.index');
    Route::get('/bodas/{wedding}/planeacion/mesas', [WeddingPlanningController::class, 'seating'])->name('weddings.planning.seating');
    Route::get('/bodas/{wedding}/planeacion/logistica', [WeddingPlanningController::class, 'logistics'])->name('weddings.planning.logistics');
    Route::get('/bodas/{wedding}/finanzas', [WeddingFinanceController::class, 'index'])->name('weddings.finance.index');
    Route::get('/bodas/{wedding}/regalos', [GiftController::class, 'manage'])->name('weddings.gifts.manage');
    Route::post('/bodas/{wedding}/regalos', [GiftController::class, 'store'])->name('weddings.gifts.store');
    Route::patch('/bodas/{wedding}/regalos/reservas/{reservation}/liberar', [GiftController::class, 'release'])->name('weddings.gifts.reservations.release');
    Route::post('/bodas/{wedding}/finanzas/conceptos', [WeddingFinanceController::class, 'storeItem'])->name('weddings.finance.items.store');
    Route::post('/bodas/{wedding}/finanzas/cotizaciones', [WeddingFinanceController::class, 'storeQuote'])->name('weddings.finance.quotes.store');
    Route::post('/bodas/{wedding}/finanzas/conceptos/{item}/pagos', [WeddingFinanceController::class, 'storePayment'])->name('weddings.finance.payments.store');
    Route::post('/bodas/{wedding}/finanzas/conceptos/{item}/comprobantes', [FinanceAttachmentController::class, 'store'])->name('weddings.finance.attachments.store');
    Route::get('/bodas/{wedding}/finanzas/comprobantes/{attachment}', [FinanceAttachmentController::class, 'download'])->name('weddings.finance.attachments.download');
    Route::post('/bodas/{wedding}/planeacion/tareas', [WeddingPlanningController::class, 'storeTask'])->name('weddings.planning.tasks.store');
    Route::patch('/bodas/{wedding}/planeacion/tareas/{task}', [WeddingPlanningController::class, 'updateTask'])->name('weddings.planning.tasks.update');
    Route::post('/bodas/{wedding}/planeacion/agenda', [WeddingPlanningController::class, 'storeAgenda'])->name('weddings.planning.agenda.store');
    Route::post('/bodas/{wedding}/planeacion/propuestas', [WeddingPlanningController::class, 'storeProposal'])->name('weddings.planning.proposals.store');
    Route::post('/bodas/{wedding}/planeacion/propuestas/{proposal}/versiones', [WeddingPlanningController::class, 'reviseProposal'])->name('weddings.planning.proposals.revise');
    Route::post('/bodas/{wedding}/planeacion/participantes', [WeddingPlanningController::class, 'storeParticipant'])->name('weddings.planning.participants.store');
    Route::post('/bodas/{wedding}/planeacion/menu', [WeddingPlanningController::class, 'storeMenu'])->name('weddings.planning.menu.store');
    Route::post('/bodas/{wedding}/planeacion/mesas', [WeddingPlanningController::class, 'storeTable'])->name('weddings.planning.tables.store');
    Route::post('/bodas/{wedding}/planeacion/logistica', [WeddingPlanningController::class, 'storeLogistic'])->name('weddings.planning.logistics.store');
    Route::post('/bodas/{wedding}/planeacion/inspiracion', [WeddingPlanningController::class, 'storeInspiration'])->name('weddings.planning.inspirations.store');
    Route::patch('/bodas/{wedding}/planeacion/integrantes/{member}/mesa', [WeddingPlanningController::class, 'assignTable'])->name('weddings.planning.members.assign-table');
    Route::patch('/bodas/{wedding}/planeacion/propuestas/{proposal}', [WeddingPlanningController::class, 'decideProposal'])->name('weddings.planning.proposals.decide');
    Route::put('/bodas/{wedding}/entrevista', [WeddingInterviewController::class, 'update'])->name('weddings.interview.update');
    Route::get('/notificaciones', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notificaciones/leidas', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');
    Route::patch('/notificaciones/{notification}/leida', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile/comunicacion', [CommunicationPreferenceController::class, 'edit'])->name('profile.communication.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/comunicacion', [CommunicationPreferenceController::class, 'update'])->name('profile.communication.update');
    Route::post('/profile/comunicacion/simular', [CommunicationPreferenceController::class, 'simulate'])->name('profile.communication.simulate');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
