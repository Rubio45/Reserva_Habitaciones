<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rate_plans', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('cancellation_policy')->nullable();
            $table->string('meal_plan', 100)->nullable();
            $table->boolean('is_refundable')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique('code');
        });
    }

    public function down()
    {
        Schema::dropIfExists('rate_plans');
    }
};