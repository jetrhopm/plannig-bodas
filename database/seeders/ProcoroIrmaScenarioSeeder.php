<?php

namespace Database\Seeders;

use App\Models\AdditionalSeatRequest;
use App\Models\GiftReservation;
use App\Models\GuestFamily;
use App\Models\RsvpResponse;
use App\Models\User;
use App\Models\Wedding;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProcoroIrmaScenarioSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $admin = User::where('email', 'admin@local.test')->firstOrFail();
            $coordinator = User::where('email', 'coordinador@local.test')->firstOrFail();
            $partner = User::where('email', 'pareja@local.test')->firstOrFail();

            $existing = Wedding::where('slug', 'pruebas-procoro-irma')->first();
            if ($existing) {
                $existing->delete();
            }

            $wedding = Wedding::create([
                'name' => 'Procoro & Irma · Pruebas',
                'slug' => 'pruebas-procoro-irma',
                'status' => 'contracted',
                'support_type' => 'full',
                'authorized_capacity' => 80,
                'timezone' => 'America/Mexico_City',
                'coordinator_id' => $coordinator->id,
                'settings' => ['scenario' => 'qa', 'notes' => 'Datos ficticios para pruebas manuales.'],
            ]);

            $wedding->memberships()->createMany([
                ['user_id' => $coordinator->id, 'relationship' => 'coordinator', 'permissions' => ['edit_wedding' => true, 'scan_passes' => true, 'view_progress' => true, 'create_requests' => true]],
                ['user_id' => $partner->id, 'relationship' => 'partner', 'permissions' => ['view_progress' => true, 'create_requests' => true, 'share_invitation' => true]],
            ]);
            $wedding->events()->createMany([
                ['name' => 'Ceremonia Procoro & Irma', 'type' => 'ceremony', 'event_date' => '2027-05-22', 'event_time' => '16:30', 'venue' => 'Jardín Los Arcos', 'address' => 'Av. de las Flores 220, Cuernavaca, Morelos', 'is_primary' => true, 'visibility' => ['guest' => true]],
                ['name' => 'Recepción y cena', 'type' => 'reception', 'event_date' => '2027-05-22', 'event_time' => '18:30', 'venue' => 'Salón Los Arcos', 'address' => 'Av. de las Flores 220, Cuernavaca, Morelos', 'is_primary' => false, 'visibility' => ['guest' => true]],
            ]);
            $wedding->interview()->create(['status' => 'draft', 'current_step' => 2, 'answers' => ['style' => 'Jardín romántico', 'colors' => ['marfil', 'rosa palo', 'verde olivo']]]);
            $wedding->services()->createMany([
                ['name' => 'Banquete vegetariano', 'category' => 'banquete', 'owner' => 'company', 'status' => 'contracted', 'estimated_cost' => 58000, 'details' => ['guests' => 80]],
                ['name' => 'Fotografía y video', 'category' => 'foto_video', 'owner' => 'couple', 'status' => 'included', 'estimated_cost' => 24500, 'details' => ['hours' => 10]],
                ['name' => 'Flores y ambientación', 'category' => 'decoracion', 'owner' => 'company', 'status' => 'pending', 'estimated_cost' => 18000, 'details' => ['palette' => 'rosa palo']],
            ]);
            $wedding->responsibilities()->createMany([
                ['label' => 'Confirmar prueba de menú', 'owner' => 'couple', 'status' => 'pending', 'due_date' => '2027-04-10', 'notes' => 'Elegir plato principal y postre.'],
                ['label' => 'Enviar contrato de música', 'owner' => 'company', 'status' => 'in_progress', 'due_date' => '2027-03-28', 'notes' => 'Pendiente firma del proveedor.'],
            ]);
            $wedding->tasks()->createMany([
                ['title' => 'Revisar lista de invitados', 'status' => 'pending', 'priority' => 'high', 'assigned_to' => $coordinator->id, 'due_date' => '2027-04-01', 'notes' => 'Escenario pendiente.'],
                ['title' => 'Confirmar proveedor floral', 'status' => 'in_progress', 'priority' => 'normal', 'assigned_to' => $coordinator->id, 'due_date' => '2027-04-07', 'notes' => 'Cotización recibida.'],
                ['title' => 'Definir música de entrada', 'status' => 'completed', 'priority' => 'low', 'assigned_to' => $partner->id, 'due_date' => '2027-03-15', 'notes' => 'Lista validada por la pareja.'],
            ]);
            $wedding->proposals()->createMany([
                ['proposal_key' => Str::uuid(), 'version' => 1, 'title' => 'Propuesta floral primavera', 'description' => 'Arco con rosas, lisianthus y follaje verde.', 'amount' => 18000, 'status' => 'sent', 'created_by' => $coordinator->id],
                ['proposal_key' => Str::uuid(), 'version' => 1, 'title' => 'Propuesta de iluminación', 'description' => 'Guirnaldas cálidas para jardín y pista.', 'amount' => 12500, 'status' => 'draft', 'created_by' => $coordinator->id],
            ]);
            $wedding->agendaItems()->createMany([
                ['title' => 'Llegada de proveedores', 'starts_at' => '2027-05-22 12:00:00', 'ends_at' => '2027-05-22 13:00:00', 'location' => 'Jardín Los Arcos', 'category' => 'logistics', 'notes' => 'Acceso por puerta norte.'],
                ['title' => 'Ceremonia', 'starts_at' => '2027-05-22 16:30:00', 'ends_at' => '2027-05-22 17:30:00', 'location' => 'Jardín principal', 'category' => 'ceremony', 'notes' => 'Música en vivo.'],
            ]);
            $wedding->participants()->createMany([
                ['name' => 'Mariana Cruz', 'role' => 'Coordinadora de día', 'phone' => '7775550101', 'schedule_note' => 'Disponible desde las 11:00.'],
                ['name' => 'Ricardo León', 'role' => 'Fotógrafo', 'phone' => '7775550102', 'schedule_note' => 'Montaje 14:00.'],
            ]);
            $wedding->menuOptions()->createMany([
                ['name' => 'Filete en salsa de vino', 'description' => 'Opción principal con guarnición de temporada.', 'is_active' => true],
                ['name' => 'Risotto de hongos', 'description' => 'Opción vegetariana.', 'is_active' => true],
            ]);
            $logistics = $wedding->logistics()->createMany([
                ['type' => 'vendor', 'name' => 'Florería Azahar', 'status' => 'quoted', 'contact' => '7775550201', 'notes' => 'Montaje floral desde las 10:00.'],
                ['type' => 'transport', 'name' => 'Traslados Morelos', 'status' => 'confirmed', 'contact' => '7775550202', 'notes' => 'Camioneta para familia.'],
            ]);
            $wedding->vendorQuotes()->create(['wedding_logistic_id' => $logistics->first()->id, 'vendor_name' => 'Florería Azahar', 'title' => 'Cotización floral inicial', 'details' => 'Arco, centro de mesa y ramo.', 'amount' => 18000, 'valid_until' => '2027-04-01', 'status' => 'received']);
            $wedding->inspirations()->create(['title' => 'Arco floral de ceremonia', 'reference_url' => 'https://example.test/inspiracion-arco-floral', 'category' => 'decoracion', 'note' => 'Referencia ficticia para pruebas.']);

            $tableA = $wedding->tables()->create(['label' => 'Mesa Bugambilia', 'capacity' => 8]);
            $wedding->tables()->create(['label' => 'Mesa Jazmín', 'capacity' => 8]);

            $riveraToken = 'qa-procoro-irma-rivera-request-2027';
            $rivera = $this->family($wedding, 'Familia Rivera', 'Andrea Rivera', 'andrea.rivera@example.test', '7775551001', 4, 3, 'confirmed', $riveraToken);
            $this->members($rivera, [
                ['Andrea Rivera', true, 'Vegetariana', null], ['Miguel Rivera', true, null, null], ['Lucía Rivera', true, null, 'Acceso sin escalones'],
            ], $tableA->id);
            RsvpResponse::create(['guest_family_id' => $rivera->id, 'idempotency_key' => Str::uuid(), 'response' => 'confirmed', 'attending_count' => 3, 'allocation_before' => 4, 'allocation_after' => 4, 'member_details' => [], 'responded_at' => now()->subDays(2)]);
            AdditionalSeatRequest::create(['guest_family_id' => $rivera->id, 'requested_count' => 2, 'status' => 'pending', 'request_note' => 'Deseamos incluir a los abuelos.', 'decision_reason' => null]);

            $soto = $this->family($wedding, 'Familia Soto', 'Carlos Soto', 'carlos.soto@example.test', '7775551002', 2, 0, 'declined', 'qa-procoro-irma-soto-declined-2027');
            $this->members($soto, [['Carlos Soto', false, null, null], ['Mónica Soto', false, null, null]]);
            RsvpResponse::create(['guest_family_id' => $soto->id, 'idempotency_key' => Str::uuid(), 'response' => 'declined', 'attending_count' => 0, 'allocation_before' => 2, 'allocation_after' => 2, 'member_details' => [], 'responded_at' => now()->subDay()]);

            $flores = $this->family($wedding, 'Familia Flores', 'Renata Flores', 'renata.flores@example.test', '7775551003', 2, 2, 'confirmed', 'qa-procoro-irma-flores-confirmed-2027');
            $this->members($flores, [['Renata Flores', true, 'Sin gluten', null], ['Diego Flores', true, null, null]]);
            $this->family($wedding, 'Familia Pineda', 'Valeria Pineda', 'valeria.pineda@example.test', '7775551004', 5, null, 'pending', 'qa-procoro-irma-pineda-pending-2027');

            $blender = $wedding->gifts()->create(['name' => 'Licuadora profesional', 'description' => 'Regalo reservado por una familia confirmada.', 'quantity' => 1, 'reserved_quantity' => 1, 'reference_url' => 'https://www.amazon.com.mx/dp/B0TESTPROCOROIRMA', 'is_active' => true]);
            GiftReservation::create(['wedding_gift_id' => $blender->id, 'guest_name' => 'Andrea Rivera · Familia Rivera', 'guest_email' => 'andrea.rivera@example.test', 'quantity' => 1, 'idempotency_key' => Str::uuid()]);
            $wedding->gifts()->createMany([
                ['name' => 'Juego de sábanas', 'description' => 'Disponible para reservar.', 'quantity' => 2, 'reserved_quantity' => 0, 'reference_url' => 'https://example.test/regalo-sabanas', 'is_active' => true],
                ['name' => 'Cena para dos', 'description' => 'Regalo ya reservado y liberado, útil para pruebas de trazabilidad.', 'quantity' => 1, 'reserved_quantity' => 0, 'reference_url' => 'https://example.test/regalo-cena', 'is_active' => true],
            ]);

            $income = $wedding->financeItems()->create(['type' => 'income', 'description' => 'Anticipo de Procoro e Irma', 'amount' => 35000, 'due_date' => '2027-03-01', 'status' => 'paid', 'visible_to_couple' => true, 'notes' => 'Pago de prueba registrado.']);
            $income->payments()->create(['amount' => 35000, 'paid_at' => '2027-03-01', 'method' => 'transfer', 'reference' => 'QA-PROC-001', 'recorded_by' => $admin->id]);
            $wedding->financeItems()->create(['type' => 'expense', 'description' => 'Anticipo de decoración floral', 'amount' => 9000, 'due_date' => '2027-04-15', 'status' => 'pending', 'visible_to_couple' => false, 'notes' => 'Costo interno de prueba.']);
        });
    }

    private function family(Wedding $wedding, string $label, string $name, string $email, string $phone, int $allocation, ?int $attending, string $status, string $token): GuestFamily
    {
        return $wedding->guestFamilies()->create(['label' => $label, 'responsible_name' => $name, 'responsible_email' => $email, 'responsible_phone' => $phone, 'original_allocation' => $allocation, 'current_allocation' => $allocation, 'rsvp_status' => $status, 'attending_count' => $attending, 'invite_token_hash' => hash('sha256', $token)]);
    }

    /** @param array<int, array{0:string,1:bool,2:?string,3:?string}> $members */
    private function members(GuestFamily $family, array $members, ?int $tableId = null): void
    {
        foreach ($members as $position => [$name, $attending, $dietary, $accessibility]) {
            $family->members()->create(['position' => $position + 1, 'display_name' => $name, 'attending' => $attending, 'dietary_restrictions' => $dietary, 'accessibility_needs' => $accessibility, 'wedding_table_id' => $attending ? $tableId : null]);
        }
    }
}
