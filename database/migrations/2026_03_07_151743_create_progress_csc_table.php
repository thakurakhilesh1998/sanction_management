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
        Schema::create('progress_csc', function (Blueprint $table) {
            $table->id();
            $table->date('p_update');
            $table->string('completion_percentage')->nullable();
            $table->text('remarks')->nullable();
            $table->string('work');
            $table->string('district')->nullable();
            $table->string('block')->nullable();
            $table->string('gp')->nullable();
            $table->timestamps();
            $table->foreign('work')
                  ->references('work')
                  ->on('csc_sanction')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progress_csc');
    }
};
