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
        Schema::table('image', function (Blueprint $table) {
            $table->string('work_started_image')->nullable()->after('image_path');
            $table->string('work_partial_image')->nullable()->after('work_started_image');
            $table->string('work_completed_image')->nullable()->after('work_partial_image');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('image', function (Blueprint $table) {
            $table->drop('work_started_image');
            $table->drop('work_partial_image');
            $table->drop('work_completed_image');

        });
    }
};
