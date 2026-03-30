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
        Schema::table('votes', function (Blueprint $table) {
            $table->integer('votes_count')->default(1);
            $table->decimal('amount', 15, 2)->nullable();
            $table->string('payment_id')->nullable();
            $table->string('status')->default('pending'); // pending, confirmed, failed
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            $table->dropColumn(['votes_count', 'amount', 'payment_id', 'status']);
        });
    }
};
