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
        Schema::create('progress_img_csc', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('progress_id');
            $table->string('work_started_image')->nullable();
            $table->string('work_partial_image')->nullable();
            $table->string('work_completed_image')->nullable();

            $table->timestamps();
            $table->foreign('progress_id')
                  ->references('id')
                  ->on('progress_csc')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progress_img_csc');
    }
};
