<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pri_asset_submissions', function (Blueprint $table) {
            $table->id();

            // Gram Panchayat
            $table->unsignedBigInteger('gp_id');

            // Submission status
            $table->enum('status', [
                'draft',
                'submitted'
            ])->default('draft');

            // Final submission details
            $table->timestamp('submitted_at')->nullable();
            $table->unsignedBigInteger('submitted_by')->nullable();

            $table->timestamps();

            // One submission record per Gram Panchayat
            $table->unique('gp_id');

            // Link with GP master table
            $table->foreign('gp_id')
                ->references('id')
                ->on('gp_list')
                ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pri_asset_submissions');
    }
};