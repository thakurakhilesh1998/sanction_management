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
        Schema::create('progress', function (Blueprint $table) {
            $table->id();
            $table->decimal('completion_percentage', 5, 2);
            $table->date('p_update');
            $table->enum('p_isComplete',['yes','no']);
            $table->string('p_uc')->nullable()->default(null);
            $table->string('p_image')->nullable()->default(null);
            $table->unsignedBigInteger('sanction_id');
            $table->text('remarks')->nullable()->default(null);
            $table->timestamps();
            $table->foreign('sanction_id')->references('id')->on('sanction')->onDelete('cascade');;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progress');
    }
};
