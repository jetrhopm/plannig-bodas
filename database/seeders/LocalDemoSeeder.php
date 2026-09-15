<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Wedding;
use App\Models\WeddingEvent;
use App\Models\WeddingMembership;
use App\Models\WeddingInterview;
use App\Models\WeddingResponsibility;
use App\Models\WeddingService;
use App\Models\WeddingServiceTemplate;
use App\Models\GuestFamily;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class LocalDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (app()->environment('production')) {
            throw new \RuntimeException('Seeder local bloqueado en producción.');
        }
        $users = [
            ['Administrador local', 'admin@local.test', 'admin'], ['Coordinador local', 'coordinador@local.test', 'coordinator'],
            ['Finanzas local', 'finanzas@local.test', 'finance'], ['Recepción local', 'recepcion@local.test', 'reception'],
            ['Sofía Martínez', 'pareja@local.test', 'couple'], ['Daniel Herrera', 'pareja2@local.test', 'couple'], ['Andrea Cruz', 'otra.pareja@local.test', 'couple'],
        ];
        foreach ($users as [$name, $email, $role]) {
            User::updateOrCreate(['email' => $email], ['name' => $name, 'role' => $role, 'password' => Hash::make('password'), 'email_verified_at' => now()]);
        }
        $coordinator = User::where('email', 'coordinador@local.test')->firstOrFail();
        $weddingA = Wedding::updateOrCreate(['slug' => 'sofia-y-daniel'], ['name' => 'Sofía & Daniel', 'status' => 'preparation', 'support_type' => 'full', 'authorized_capacity' => 120, 'timezone' => 'America/Mexico_City', 'coordinator_id' => $coordinator->id]);
        $weddingB = Wedding::updateOrCreate(['slug' => 'andrea-y-emilio'], ['name' => 'Andrea & Emilio', 'status' => 'contracted', 'support_type' => 'shared', 'authorized_capacity' => 80, 'timezone' => 'America/Mexico_City', 'coordinator_id' => $coordinator->id]);
        $dateA = now()->addMonths(4)->toDateString();
        $dateB = now()->addMonths(7)->toDateString();
        WeddingEvent::updateOrCreate(['wedding_id' => $weddingA->id, 'type' => 'reception'], ['name' => 'Recepción', 'event_date' => $dateA, 'event_time' => '18:00', 'starts_at' => Carbon::createFromFormat('Y-m-d H:i', "$dateA 18:00", 'America/Mexico_City')->utc(), 'venue' => 'Casa Encanto', 'is_primary' => true]);
        WeddingEvent::updateOrCreate(['wedding_id' => $weddingB->id, 'type' => 'reception'], ['name' => 'Recepción', 'event_date' => $dateB, 'event_time' => '17:00', 'starts_at' => Carbon::createFromFormat('Y-m-d H:i', "$dateB 17:00", 'America/Mexico_City')->utc(), 'venue' => 'Hacienda Los Olivos', 'is_primary' => true]);
        $planning = WeddingServiceTemplate::firstOrCreate(['name' => 'Planeación integral'], ['category' => 'Planeación', 'description' => 'Coordinación completa del evento.', 'default_owner' => 'company']);
        $venue = WeddingServiceTemplate::firstOrCreate(['name' => 'Salón y montaje'], ['category' => 'Logística', 'description' => 'Gestión del recinto y montaje.', 'default_owner' => 'company']);
        WeddingService::updateOrCreate(['wedding_id' => $weddingA->id, 'name' => $planning->name], ['wedding_service_template_id' => $planning->id, 'category' => $planning->category, 'owner' => 'company', 'status' => 'included']);
        WeddingService::updateOrCreate(['wedding_id' => $weddingA->id, 'name' => $venue->name], ['wedding_service_template_id' => $venue->id, 'category' => $venue->category, 'owner' => 'company', 'status' => 'included']);
        WeddingResponsibility::updateOrCreate(['wedding_id' => $weddingA->id, 'label' => 'Validar lista inicial de invitados'], ['owner' => 'couple', 'status' => 'pending', 'due_date' => now()->addWeeks(2)->toDateString()]);
        WeddingInterview::updateOrCreate(['wedding_id' => $weddingA->id], ['current_step' => 2, 'status' => 'draft', 'answers' => ['contact_name' => 'Sofía Martínez', 'contact_email' => 'pareja@local.test', 'contact_phone' => '555 010 2020', 'estimated_budget' => 250000], 'last_saved_at' => now()]);
        GuestFamily::updateOrCreate(['wedding_id' => $weddingA->id, 'label' => 'Familia García'], ['responsible_name' => 'Elena García', 'original_allocation' => 6, 'current_allocation' => 4, 'rsvp_status' => 'confirmed', 'attending_count' => 4, 'invite_token_hash' => hash('sha256', 'demo-garcia-six-to-four')]);
        GuestFamily::updateOrCreate(['wedding_id' => $weddingA->id, 'label' => 'Familia Torres'], ['responsible_name' => 'Jorge Torres', 'original_allocation' => 3, 'current_allocation' => 3, 'rsvp_status' => 'pending', 'invite_token_hash' => hash('sha256', 'demo-torres-pending')]);
        GuestFamily::updateOrCreate(['wedding_id' => $weddingB->id, 'label' => 'Familia Cruz'], ['responsible_name' => 'Paola Cruz', 'original_allocation' => 2, 'current_allocation' => 0, 'rsvp_status' => 'declined', 'attending_count' => 0, 'invite_token_hash' => hash('sha256', 'demo-cruz-declined')]);
        foreach ([['pareja@local.test', $weddingA, 'partner'], ['pareja2@local.test', $weddingA, 'partner'], ['otra.pareja@local.test', $weddingB, 'partner'], ['coordinador@local.test', $weddingA, 'coordinator'], ['coordinador@local.test', $weddingB, 'coordinator'], ['finanzas@local.test', $weddingA, 'finance'], ['recepcion@local.test', $weddingA, 'reception']] as [$email, $wedding, $relationship]) {
            $permissions = match ($relationship) {
                'partner' => ['view_progress' => true, 'approve_proposals' => true],
                'finance' => ['view_progress' => true, 'view_finance' => true],
                'reception' => ['view_progress' => true, 'scan_passes' => true],
                default => ['view_progress' => true, 'edit_wedding' => true],
            };
            WeddingMembership::updateOrCreate(['wedding_id' => $wedding->id, 'user_id' => User::where('email', $email)->value('id')], ['relationship' => $relationship, 'permissions' => $permissions]);
        }
        $admin = User::where('email', 'admin@local.test')->firstOrFail();
        $admin->notifications()->firstOrCreate(['id' => '00000000-0000-4000-8000-000000000001'], ['type' => 'local-demo', 'data' => ['title' => 'Demostración local preparada', 'description' => 'Las cuentas y bodas de prueba están disponibles en este entorno local.', 'importance' => 'normal']]);
        $coordinator->notifications()->firstOrCreate(['id' => '00000000-0000-4000-8000-000000000002'], ['type' => 'local-demo', 'data' => ['title' => 'Boda asignada', 'description' => 'Sofía & Daniel está disponible en tu panel operativo.', 'importance' => 'high']]);
    }
}
