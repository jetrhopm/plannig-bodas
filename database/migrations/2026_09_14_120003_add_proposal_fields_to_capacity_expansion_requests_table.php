<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('capacity_expansion_requests', function (Blueprint $table) {
            $table->unsignedInteger('proposal_version')->default(0)->after('status');
            $table->foreignId('proposed_by')->nullable()->after('additional_cost')->constrained('users')->nullOnDelete();
            $table->timestamp('proposed_at')->nullable()->after('proposed_by');
            $table->foreignId('responded_by')->nullable()->after('proposed_at')->constrained('users')->nullOnDelete();
            $table->timestamp('responded_at')->nullable()->after('responded_by');
        });
    }

    public function down(): void
    {
        Schema::table('capacity_expansion_requests', function (Blueprint $table) {
            $table->dropConstrainedForeignId('responded_by');
            $table->dropConstrainedForeignId('proposed_by');
            $table->dropColumn(['proposal_version', 'proposed_at', 'responded_at']);
        });
    }
};
