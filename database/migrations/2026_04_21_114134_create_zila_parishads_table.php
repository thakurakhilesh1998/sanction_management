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
        Schema::create('zila_parishads', function (Blueprint $table) {
            $table->id();
            $table->integer('ward_no');
            $table->string('ward_name');
             $table->enum('designation', [
                'Member',
                'Vice Chairman',
                'Chairman'
            ]);
             $table->string('name');
            $table->text('address');
            $table->string('pincode', 6);
            $table->string('mobile', 10);
             $table->enum('reservation_status', [
                'Unreserved',
                'SC',
                'SC Woman',
                'ST',
                'ST Woman',
                'OBC',
                'OBC Woman'
            ]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zila_parishads');
    }
};
