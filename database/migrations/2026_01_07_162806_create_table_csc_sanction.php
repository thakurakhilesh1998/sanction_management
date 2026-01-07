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
        Schema::create('csc_sanction', function (Blueprint $table) {
            $table->id();
            $table->string('financial_year');
            $table->string('district');
            $table->string('block');
            $table->string('gp');
            $table->decimal('san_amount',20,2);
            $table->date('sanction_date');
            $table->string('sanction_head');
            $table->string('sanction_purpose');
            $table->string('agency');
            $table->string('work',255)->index();
            $table->string('san_pdf')->nullable();
            $table->string('uc')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('csc_sanction');
    }
};
