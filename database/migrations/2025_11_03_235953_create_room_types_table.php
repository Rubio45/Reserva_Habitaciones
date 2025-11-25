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
    Schema::create('room_types', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->string('code', 32)->unique();
        $table->string('name', 120);
        $table->text('description')->nullable();
        $table->tinyInteger('base_occupancy')->unsigned()->default(2);
        $table->tinyInteger('max_occupancy')->unsigned()->default(4);
        $table->string('bed_config', 120)->nullable();
        $table->decimal('area_m2', 6, 2)->nullable();
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_types');
    }
};
