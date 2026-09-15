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
        Schema::create('guest_family_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guest_family_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('position');
            $table->string('display_name')->nullable();
            $table->boolean('attending')->nullable();
            $table->text('dietary_restrictions')->nullable();
            $table->text('accessibility_needs')->nullable();
            $table->timestamps();
            $table->unique(['guest_family_id', 'position']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guest_family_members');
    }
};
