<?php
use Illuminate\Database\Migrations\Migration; use Illuminate\Database\Schema\Blueprint; use Illuminate\Support\Facades\Schema;
return new class extends Migration { public function up(): void { Schema::create('communication_attempts', function (Blueprint $table) { $table->id(); $table->foreignId('user_id')->constrained()->cascadeOnDelete(); $table->string('channel'); $table->string('status')->default('simulated'); $table->string('subject'); $table->text('body')->nullable(); $table->timestamps(); }); } public function down(): void { Schema::dropIfExists('communication_attempts'); } };
