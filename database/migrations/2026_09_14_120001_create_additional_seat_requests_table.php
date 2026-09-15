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
        Schema::create('additional_seat_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guest_family_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('requested_count');
            $table->unsignedInteger('approved_count')->nullable();
            $table->string('status')->default('pending')->index();
            $table->text('request_note')->nullable();
            $table->text('decision_reason')->nullable();
            $table->foreignId('decided_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('decided_at')->nullable();
            $table->timestamps();
            $table->index(['guest_family_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('additional_seat_requests');
    }
};
