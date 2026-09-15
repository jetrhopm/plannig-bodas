<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration { public function up(): void { Schema::create('vendor_quotes', function (Blueprint $table) { $table->id(); $table->foreignId('wedding_id')->constrained()->cascadeOnDelete(); $table->foreignId('wedding_logistic_id')->nullable()->constrained()->nullOnDelete(); $table->string('vendor_name'); $table->string('title'); $table->text('details')->nullable(); $table->decimal('amount', 12, 2); $table->date('valid_until')->nullable(); $table->string('status')->default('received'); $table->timestamps(); $table->index(['wedding_id','status']); }); } public function down(): void { Schema::dropIfExists('vendor_quotes'); } };
