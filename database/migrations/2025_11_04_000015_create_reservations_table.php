<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 20)->unique();
            $table->foreignId('guest_id')->constrained('guests')->restrictOnDelete();
            // Usamos string en lugar de ENUM nativo para mayor flexibilidad
            // Valores posibles: PENDING, CONFIRMED, CHECKED_IN, CHECKED_OUT, CANCELLED, NO_SHOW
            $table->string('status', 20)->default('PENDING');
            // Valores posibles: DIRECT, PHONE, WALKIN, OTA
            $table->string('channel', 20)->default('DIRECT');
            $table->date('check_in');
            $table->date('check_out');
            $table->tinyInteger('adults')->default(2);
            $table->tinyInteger('children')->default(0);
            $table->char('currency', 3)->default('NIO');
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            // Nota: MySQL no siempre aplica CHECK constraints, pero la validación se hace a nivel de aplicación
            // CHECK lógico: check_out > check_in (validado en Form Request con 'after:check_in')
        });
    }

    public function down()
    {
        Schema::dropIfExists('reservations');
    }
};