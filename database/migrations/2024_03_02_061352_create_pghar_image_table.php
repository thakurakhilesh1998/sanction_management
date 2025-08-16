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
        Schema::create('pghar_image', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gp_id');
            $table->string('image_path');
            $table->foreign('gp_id')->references('id')->on('gp_list')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pghar_image');
    }
};
