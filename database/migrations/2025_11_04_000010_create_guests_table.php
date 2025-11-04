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
    Schema::create('guests', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->string('first_name', 120);
        $table->string('last_name', 120)->nullable();
        $table->string('email', 120)->nullable()->index();
        $table->string('phone', 40)->nullable();
        $table->string('document_type', 32)->nullable();
        $table->string('document_number', 120)->nullable();
        $table->char('country_code', 2)->nullable();
        $table->text('notes')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guests');
    }
};
