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
        Schema::create('gram_panchayats', function (Blueprint $table) {
            $table->id();

            // District (from Auth)
            $table->string('district');

            // Panchayat Samiti Name
            $table->string('ps_name', 150);

            // Gram Panchayat Name
            $table->string('gp_name', 150);

            // Designation (Pradhan / Up-Pradhan)
            $table->string('designation');

            // Personal Details
            $table->string('name', 150);
            $table->text('address');
            $table->string('pincode', 6);

            // Mobile (Unique)
            $table->string('mobile', 10)->unique();

            // Reservation Status
            $table->string('reservation_status');

            $table->timestamps();

            // Index for filtering
            $table->index(['district', 'ps_name', 'gp_name']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gram_panchayats');
    }
};
