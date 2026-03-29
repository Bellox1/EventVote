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
            $table->renameColumn('video_url', 'video_path');
        });
        Schema::table('candidates', function (Blueprint $table) {
            $table->renameColumn('video_url', 'video_path');
        });
    }

    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->renameColumn('video_path', 'video_url');
        });
        Schema::table('candidates', function (Blueprint $table) {
            $table->renameColumn('video_path', 'video_url');
        });
    }
};
