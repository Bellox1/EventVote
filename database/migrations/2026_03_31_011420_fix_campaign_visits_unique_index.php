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
        Schema::table('campaign_visits', function (Blueprint $table) {
            // Drop old unique index
            $table->dropUnique(['campaign_id', 'ip_address', 'session_id']);
            
            // Create new unique index including candidate_id
            $table->unique(['campaign_id', 'candidate_id', 'ip_address', 'session_id'], 'campaign_visits_full_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campaign_visits', function (Blueprint $table) {
            $table->dropUnique('campaign_visits_full_unique');
            $table->unique(['campaign_id', 'ip_address', 'session_id']);
        });
    }
};
