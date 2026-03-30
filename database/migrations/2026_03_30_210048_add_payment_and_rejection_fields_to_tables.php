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
        Schema::table('campaigns', function (Blueprint $table) {
            if (!Schema::hasColumn('campaigns', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable();
            }
            if (!Schema::hasColumn('campaigns', 'bank_account')) {
                $table->string('bank_account')->nullable();
            }
            if (!Schema::hasColumn('campaigns', 'vote_price')) {
                $table->integer('vote_price')->default(100);
            }
        });

        Schema::table('candidates', function (Blueprint $table) {
            if (!Schema::hasColumn('candidates', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropColumn(['rejection_reason', 'account_number', 'vote_price']);
        });

        Schema::table('candidates', function (Blueprint $table) {
            $table->dropColumn('rejection_reason');
        });
    }
};
