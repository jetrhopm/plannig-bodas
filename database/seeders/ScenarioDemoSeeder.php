<?php
namespace Database\Seeders;

use App\Models\CommunicationAttempt;
use App\Models\GiftReservation;
use App\Models\GuestAccessEntry;
use App\Models\GuestFamily;
use App\Models\User;
use App\Models\Wedding;
use App\Models\WeddingFinanceItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ScenarioDemoSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('production')) throw new \RuntimeException('Escenarios demo bloqueados en producción.');
        app(LocalDemoSeeder::class)->run();
        $admin = User::where('email', 'admin@local.test')->firstOrFail();
        $finance = User::where('email', 'finanzas@local.test')->firstOrFail();
        $reception = User::where('email', 'recepcion@local.test')->firstOrFail();
        $wedding = Wedding::where('slug', 'sofia-y-daniel')->firstOrFail();
        $torres = GuestFamily::where('wedding_id', $wedding->id)->where('label', 'Familia Torres')->firstOrFail();
        $extra = GuestFamily::updateOrCreate(['wedding_id' => $wedding->id, 'label' => 'Familia Molina'], ['responsible_name' => 'Lucía Molina', 'responsible_email' => 'lucia@example.test', 'original_allocation' => 2, 'current_allocation' => 2, 'rsvp_status' => 'confirmed', 'attending_count' => 2, 'invite_token_hash' => hash('sha256', 'demo-molina-confirmed')]);
        $extra->members()->updateOrCreate(['position' => 1], ['display_name' => 'Lucía Molina', 'attending' => true, 'dietary_restrictions' => 'Vegetariana']);
        $extra->members()->updateOrCreate(['position' => 2], ['display_name' => 'Marcos Molina', 'attending' => true, 'accessibility_needs' => 'Acceso sin escalones']);
        $wedding->tasks()->updateOrCreate(['title' => 'Confirmar montaje floral'], ['priority' => 'high', 'status' => 'in_progress', 'due_date' => now()->addDays(10)]);
        $wedding->agendaItems()->updateOrCreate(['title' => 'Montaje'], ['category' => 'setup', 'starts_at' => now()->addMonths(4)->setTime(10, 0), 'location' => 'Casa Encanto']);
        $wedding->participants()->updateOrCreate(['name' => 'Valeria Gómez'], ['role' => 'Testigo', 'schedule_note' => 'Llegar 30 minutos antes']);
        $wedding->menuOptions()->updateOrCreate(['name' => 'Pollo al romero'], ['description' => 'Con guarnición de temporada']);
        $table = $wedding->tables()->updateOrCreate(['label' => 'Mesa 1'], ['capacity' => 8]);
        $extra->members()->update(['wedding_table_id' => $table->id]);
        $wedding->logistics()->updateOrCreate(['type' => 'hotel', 'name' => 'Hotel Jardín'], ['status' => 'recommended', 'contact' => 'reservas@hotel.test', 'notes' => 'Recomendación; no es una reserva administrada.']);
        $wedding->inspirations()->updateOrCreate(['title' => 'Paleta terracota'], ['category' => 'Decoración', 'note' => 'Flores naturales y velas cálidas.']);
        $charge = WeddingFinanceItem::updateOrCreate(['wedding_id' => $wedding->id, 'description' => 'Anticipo de coordinación'], ['type' => 'charge', 'amount' => 25000, 'due_date' => now()->addDays(7), 'visible_to_couple' => true, 'status' => 'pending']);
        if (!$charge->payments()->exists()) $charge->payments()->create(['amount' => 10000, 'paid_at' => now()->subDays(2), 'method' => 'transfer', 'reference' => 'DEMO-ANTICIPO', 'recorded_by' => $finance->id]);
        WeddingFinanceItem::updateOrCreate(['wedding_id' => $wedding->id, 'description' => 'Costo interno de montaje'], ['type' => 'cost', 'amount' => 8500, 'visible_to_couple' => false, 'status' => 'pending']);
        $wedding->vendorQuotes()->updateOrCreate(['vendor_name' => 'Flores Brisa', 'title' => 'Arreglo floral'], ['amount' => 12000, 'details' => 'Centro de mesa y arco.', 'valid_until' => now()->addDays(14), 'status' => 'received']);
        $gift = $wedding->gifts()->updateOrCreate(['name' => 'Juego de sábanas'], ['description' => 'Algodón blanco, tamaño queen.', 'quantity' => 2, 'reserved_quantity' => 1, 'is_active' => true]);
        GiftReservation::firstOrCreate(['wedding_gift_id' => $gift->id, 'idempotency_key' => '33333333-3333-4333-8333-333333333333'], ['guest_name' => 'Carla Ruiz', 'guest_email' => 'carla@example.test', 'quantity' => 1]);
        CommunicationAttempt::firstOrCreate(['user_id' => $admin->id, 'channel' => 'email', 'subject' => 'Demostración local'], ['status' => 'simulated', 'body' => 'No se envió ningún correo externo.']);
        GuestAccessEntry::firstOrCreate(['guest_family_id' => $extra->id, 'idempotency_key' => '44444444-4444-4444-8444-444444444444'], ['user_id' => $reception->id, 'count' => 1, 'entered_at' => now()]);
        $torres->additionalSeatRequests()->firstOrCreate(['requested_count' => 1, 'status' => 'pending'], ['request_note' => 'Solicitan incluir a una persona adicional.']);
    }
}
