<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('room_type_amenity', function (Blueprint $table) {
            // Si prefieres nullable puedes usar: $table->timestamps();
            $table->timestamp('created_at')->useCurrent()->after('amenity_id');
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate()->after('created_at');
        });
    }

    public function down(): void
    {
        Schema::table('room_type_amenity', function (Blueprint $table) {
            $table->dropColumn(['created_at','updated_at']);
        });
    }
};
