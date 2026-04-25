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
        Schema::disableForeignKeyConstraints();

        Schema::table('campaign_visits', function (Blueprint $table) {
            // Drop foreign key first because index is needed for it
            $table->dropForeign(['campaign_id']);

            // Add candidate_id column if not exists
            if (!Schema::hasColumn('campaign_visits', 'candidate_id')) {
                $table->foreignId('candidate_id')->nullable()->after('campaign_id')->constrained()->onDelete('cascade');
            }
            
            // Drop old unique index
            $table->dropUnique(['campaign_id', 'ip_address', 'session_id']);
            
            // Create new unique index including candidate_id
            $table->unique(['campaign_id', 'candidate_id', 'ip_address', 'session_id'], 'campaign_visits_full_unique');

            // Re-add foreign key for campaign_id
            $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('cascade');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::table('campaign_visits', function (Blueprint $table) {
            $table->dropUnique('campaign_visits_full_unique');
            $table->unique(['campaign_id', 'ip_address', 'session_id']);
            
            if (Schema::hasColumn('campaign_visits', 'candidate_id')) {
                $table->dropForeign(['candidate_id']);
                $table->dropColumn('candidate_id');
            }
        });

        Schema::enableForeignKeyConstraints();
    }
};
