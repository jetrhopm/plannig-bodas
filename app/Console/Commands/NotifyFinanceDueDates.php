<?php
namespace App\Console\Commands;
use App\Models\WeddingFinanceItem;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
class NotifyFinanceDueDates extends Command { protected $signature = 'finance:notify-due'; protected $description = 'Genera avisos locales para cobros vencidos o próximos a vencer'; public function handle(): int { WeddingFinanceItem::query()->where('type', 'charge')->where('status', '!=', 'paid')->whereNotNull('due_date')->whereDate('due_date', '<=', now()->addDays(7))->with('wedding.memberships.user')->get()->each(function ($item) { $item->wedding->memberships->filter(fn ($member) => data_get($member->permissions, 'view_finance'))->each(fn ($member) => $member->user->notifications()->firstOrCreate(['id' => (string) Str::uuid()], ['type' => 'finance_due', 'data' => ['title' => 'Vencimiento financiero próximo', 'description' => "{$item->description} vence el {$item->due_date->format('d/m/Y')}.", 'importance' => 'high']])); }); $this->info('Avisos financieros generados.'); return self::SUCCESS; } }
