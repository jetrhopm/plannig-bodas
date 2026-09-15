<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('wedding_participants', function (Blueprint $table) { $table->id(); $table->foreignId('wedding_id')->constrained()->cascadeOnDelete(); $table->string('name'); $table->string('role'); $table->string('phone')->nullable(); $table->string('schedule_note')->nullable(); $table->timestamps(); $table->index('wedding_id'); });
        Schema::create('wedding_menu_options', function (Blueprint $table) { $table->id(); $table->foreignId('wedding_id')->constrained()->cascadeOnDelete(); $table->string('name'); $table->text('description')->nullable(); $table->boolean('is_active')->default(true); $table->timestamps(); $table->index('wedding_id'); });
        Schema::create('wedding_tables', function (Blueprint $table) { $table->id(); $table->foreignId('wedding_id')->constrained()->cascadeOnDelete(); $table->string('label'); $table->unsignedInteger('capacity'); $table->timestamps(); $table->unique(['wedding_id', 'label']); });
    }
    public function down(): void { Schema::dropIfExists('wedding_tables'); Schema::dropIfExists('wedding_menu_options'); Schema::dropIfExists('wedding_participants'); }
};
