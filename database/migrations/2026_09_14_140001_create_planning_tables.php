<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('wedding_tasks', function (Blueprint $table) { $table->id(); $table->foreignId('wedding_id')->constrained()->cascadeOnDelete(); $table->string('title'); $table->string('status')->default('pending'); $table->string('priority')->default('normal'); $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete(); $table->date('due_date')->nullable(); $table->text('notes')->nullable(); $table->timestamps(); $table->index(['wedding_id', 'status']); });
        Schema::create('wedding_agenda_items', function (Blueprint $table) { $table->id(); $table->foreignId('wedding_id')->constrained()->cascadeOnDelete(); $table->string('title'); $table->datetime('starts_at')->nullable(); $table->datetime('ends_at')->nullable(); $table->string('location')->nullable(); $table->string('category')->default('other'); $table->text('notes')->nullable(); $table->timestamps(); $table->index(['wedding_id', 'starts_at']); });
        Schema::create('wedding_proposals', function (Blueprint $table) { $table->id(); $table->foreignId('wedding_id')->constrained()->cascadeOnDelete(); $table->uuid('proposal_key'); $table->unsignedInteger('version'); $table->string('title'); $table->text('description')->nullable(); $table->decimal('amount', 12, 2)->nullable(); $table->string('status')->default('draft'); $table->text('couple_comment')->nullable(); $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete(); $table->foreignId('decided_by')->nullable()->constrained('users')->nullOnDelete(); $table->timestamp('decided_at')->nullable(); $table->timestamps(); $table->unique(['wedding_id', 'proposal_key', 'version']); $table->index(['wedding_id', 'status']); });
    }
    public function down(): void { Schema::dropIfExists('wedding_proposals'); Schema::dropIfExists('wedding_agenda_items'); Schema::dropIfExists('wedding_tasks'); }
};
