<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rate_plan_prices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('rate_plan_id')->constrained('rate_plans')->restrictOnDelete();
            $table->foreignId('room_type_id')->constrained('room_types')->restrictOnDelete();
            $table->date('date_from');
            $table->date('date_to');
            $table->tinyInteger('occupancy')->default(2);
            $table->decimal('price', 12, 2);
            $table->decimal('extra_adult', 12, 2)->default(0);
            $table->decimal('extra_child', 12, 2)->default(0);
            $table->char('currency', 3)->default('NIO');
            $table->timestamps();

            // Índice compuesto para búsquedas eficientes por (rate_plan_id, room_type_id, date_from, date_to)
            $table->index(['rate_plan_id', 'room_type_id', 'date_from', 'date_to']);

            // Nota: MySQL no siempre aplica CHECK constraints, pero la validación se hace a nivel de aplicación
            // CHECK lógico: date_to >= date_from (validado en Form Request con 'after_or_equal:date_from')
        });
    }

    public function down()
    {
        Schema::dropIfExists('rate_plan_prices');
    }
};