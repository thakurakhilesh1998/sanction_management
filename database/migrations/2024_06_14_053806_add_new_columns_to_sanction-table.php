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
            $table->string('san_pdf')->nullable();
            $table->string('san_sign_pdf')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sanction', function (Blueprint $table) {
            $table->dropColumn('san_pdf');
            $table->dropColumn('san_sign_pdf');
        });
    }
};
