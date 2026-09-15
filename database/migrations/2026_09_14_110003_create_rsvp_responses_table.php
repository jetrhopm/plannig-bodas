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
        Schema::create('rsvp_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guest_family_id')->constrained()->cascadeOnDelete();
            $table->string('idempotency_key', 80);
            $table->string('response');
            $table->unsignedInteger('attending_count')->nullable();
            $table->unsignedInteger('allocation_before');
            $table->unsignedInteger('allocation_after');
            $table->json('member_details')->nullable();
            $table->timestamp('responded_at');
            $table->timestamps();
            $table->unique(['guest_family_id', 'idempotency_key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rsvp_responses');
    }
};
