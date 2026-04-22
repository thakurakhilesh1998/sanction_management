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
        Schema::create('panchayat_samitis', function (Blueprint $table) {
            $table->id();
            // District (from Auth)
            $table->string('district');

            // Panchayat Samiti Name
            $table->string('ps_name', 150);

            // Ward Details
            $table->integer('ward_no');
            $table->string('ward_name', 100);

            // Designation
            $table->string('designation');

            // Personal Details
            $table->string('name', 150);
            $table->text('address');
            $table->string('pincode', 6);

            // Mobile (Unique)
            $table->string('mobile', 10)->unique();

            // Reservation
            $table->string('reservation_status');
            // Optional indexes (recommended)
            $table->index(['district', 'ps_name']);

            // Prevent duplicate ward in same PS
            $table->unique(['ps_name', 'ward_no']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('panchayat_samitis');
    }
};
