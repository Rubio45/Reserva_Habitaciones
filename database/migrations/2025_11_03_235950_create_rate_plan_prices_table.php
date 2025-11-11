<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rate_plan_prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rate_plan_id');
            $table->unsignedBigInteger('room_type_id');
            $table->date('date_from');
            $table->date('date_to');
            $table->integer('occupancy');
            $table->decimal('price', 10, 2);
            $table->decimal('extra_adult', 10, 2)->nullable();
            $table->decimal('extra_child', 10, 2)->nullable();
            $table->string('currency', 3);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rate_plan_prices');
    }
};