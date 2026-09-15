<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('guest_families', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wedding_id')->constrained()->cascadeOnDelete();
            $table->string('label');
            $table->string('responsible_name')->nullable();
            $table->string('responsible_email')->nullable();
            $table->string('responsible_phone')->nullable();
            $table->unsignedInteger('original_allocation');
            $table->unsignedInteger('current_allocation');
            $table->string('rsvp_status')->default('pending')->index();
            $table->unsignedInteger('attending_count')->nullable();
            $table->string('invite_token_hash', 64)->unique();
            $table->timestamp('invite_revoked_at')->nullable();
            $table->timestamps();
            $table->unique(['wedding_id', 'label']);
            $table->index(['wedding_id', 'rsvp_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guest_families');
    }
};
