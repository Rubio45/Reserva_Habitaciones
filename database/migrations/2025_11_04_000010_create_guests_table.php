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
        $table->string('last_name', 120);
        // Email con índice para búsquedas rápidas (opcional: hacer único con ->unique())
        $table->string('email', 120)->nullable()->index();
        $table->string('phone', 40)->nullable();
        $table->string('document_type', 32)->nullable();
        // Índice compuesto para búsquedas por tipo y número de documento
        $table->string('document_number', 64)->nullable();
        $table->char('country_code', 2)->nullable();
        $table->text('notes')->nullable();
        $table->timestamps();

        // Índice compuesto para búsquedas eficientes por tipo y número de documento
        $table->index(['document_type', 'document_number']);
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
