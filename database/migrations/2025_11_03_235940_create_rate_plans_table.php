<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rate_plans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 32)->unique();
            $table->string('name', 120);
            $table->text('description')->nullable();
            $table->text('cancellation_policy')->nullable();
            // Usamos string en lugar de ENUM nativo para mayor flexibilidad
            // Los valores válidos se validan a nivel de aplicación (Form Request)
            // Valores posibles: RO (Room Only), BB (Bed & Breakfast), HB (Half Board), FB (Full Board), AI (All Inclusive)
            // Alternativa: Podrías usar un PHP Enum (enum MealPlan: string) para type-safety
            $table->string('meal_plan', 4)->default('RO');
            $table->boolean('is_refundable')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rate_plans');
    }
};