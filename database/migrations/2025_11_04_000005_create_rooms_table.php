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
    Schema::create('rooms', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->unsignedBigInteger('room_type_id');
        $table->string('room_number', 32);
        $table->string('floor', 16)->nullable();
        $table->string('status', 16)->default('available'); // según tu enum
        $table->timestamps();

        $table->foreign('room_type_id')->references('id')->on('room_types')->restrictOnDelete();
        $table->unique(['room_number']); // opcional pero útil
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
