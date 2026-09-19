<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pri_assets', function (Blueprint $table) {

            $table->id();

            // Unique reference for each asset
            $table->uuid('asset_uuid')->unique();

            // Existing Gram Panchayat master table
            $table->unsignedBigInteger('gp_id');

            $table->foreign('gp_id')
                ->references('id')
                ->on('gp_list')
                ->onDelete('restrict');

            // Building details
            $table->string('asset_category');
            $table->string('asset_sub_category');
            $table->string('building_name');

            // Ownership
            $table->enum('ownership', [
                'gram_panchayat',
                'panchayat_samiti',
                'zila_parishad'
            ]);

            // Applicable only for Panchayat Ghar / Panchayat Office
            $table->enum('panchayat_office_status', [
                'owned_by_gram_panchayat',
                'taken_on_rent'
            ])->nullable();

            // Geo-location
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->decimal('location_accuracy', 8, 2)->nullable();
            $table->timestamp('location_captured_at')->nullable();

            // Building photograph
            $table->string('asset_image');

            // Asset active status
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            // Indexes
            $table->index('gp_id');
            $table->index('asset_category');
            $table->index('asset_sub_category');
            $table->index('ownership');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pri_assets');
    }
};