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
            if(!Schema::hasColumns('progress',['gp','block','district','sanction_purpose']))
            {
               throw new \Exception('Columns not found');
            }
       
        $table->foreign(['gp','block','district','sanction_purpose'],'progress_sanction_fk')
        ->references(['gp','block','district','sanction_purpose'])
        ->on('sanction')
        ->onDelete('cascade')
        ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('progress', function (Blueprint $table) {
            $table->dropForeign('progress_sanction_fk');
        });
    }
};
