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
            $table->foreignId('room_type_id')->constrained('room_types')->restrictOnDelete();
            $table->string('room_number', 32)->unique();
            $table->string('floor', 16)->nullable();
            // Usamos string en lugar de ENUM nativo para mayor flexibilidad
            // Los valores válidos se validan a nivel de aplicación (Form Request)
            // Valores posibles: AVAILABLE, OUT_OF_SERVICE, CLEANING, OCCUPIED
            $table->string('status', 20)->default('AVAILABLE');
            $table->timestamps();
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
