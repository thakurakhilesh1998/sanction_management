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
        Schema::create('progress_rd', function (Blueprint $table) {
            $table->id();
            $table->date('p_update');
            $table->string('completion_percentage');
            $table->string('remarks')->nullable();
            $table->string('work',255); 
            $table->string('district');
            $table->string('block');
            $table->foreign('work')->references('work')->on('rd_sanction')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progress_rd');
    }
};
