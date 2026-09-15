<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration { public function up(): void { Schema::create('wedding_inspirations', function (Blueprint $table) { $table->id(); $table->foreignId('wedding_id')->constrained()->cascadeOnDelete(); $table->string('title'); $table->string('category')->default('general'); $table->text('note')->nullable(); $table->string('reference_url')->nullable(); $table->timestamps(); $table->index('wedding_id'); }); } public function down(): void { Schema::dropIfExists('wedding_inspirations'); } };
