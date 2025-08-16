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
        Schema::table('pghar_image', function (Blueprint $table) {
            $table->integer('rooms')->after('image_path');
            $table->decimal('lat',8,6)->after('rooms');
            $table->decimal('long',8,6)->after('lat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pghar_image', function (Blueprint $table) {
            $table->dropColumn(['rooms','lat','long']);
        });
    }
};
