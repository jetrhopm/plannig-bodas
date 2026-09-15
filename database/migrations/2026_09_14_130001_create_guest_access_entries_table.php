<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void { Schema::create('guest_access_entries', function (Blueprint $table) { $table->id(); $table->foreignId('guest_family_id')->constrained()->cascadeOnDelete(); $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); $table->unsignedInteger('count'); $table->uuid('idempotency_key'); $table->timestamp('entered_at'); $table->timestamps(); $table->unique(['guest_family_id', 'idempotency_key']); $table->index('guest_family_id'); }); }
    public function down(): void { Schema::dropIfExists('guest_access_entries'); }
};
