<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration { public function up(): void { Schema::table('guest_family_members', function (Blueprint $table) { $table->foreignId('wedding_table_id')->nullable()->after('guest_family_id')->constrained('wedding_tables')->nullOnDelete(); }); } public function down(): void { Schema::table('guest_family_members', fn (Blueprint $table) => $table->dropConstrainedForeignId('wedding_table_id')); } };
