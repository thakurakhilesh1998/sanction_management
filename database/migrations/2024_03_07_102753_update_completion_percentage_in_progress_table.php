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
        Schema::table('progress', function (Blueprint $table) {
            $table->enum('completion_percentage', ['Tender Floated','Tender Cancelled','Tender Awarded','Work Started'])->nullable()->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('progress', function (Blueprint $table) {
            $table->enum('completion_percentage',['Tender Floated','Tender Cancelled','Tender Awarded','Work Started'])->change();
        });
    }
};
