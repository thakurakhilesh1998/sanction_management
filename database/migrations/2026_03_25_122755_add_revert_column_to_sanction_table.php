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
        Schema::table('sanction', function (Blueprint $table) {
            $table->boolean('revert')
                  ->default(false)
                  ->nullable()
                  ->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sanction', function (Blueprint $table) {
            $table->dropColumn('revert');
        });
    }
};
