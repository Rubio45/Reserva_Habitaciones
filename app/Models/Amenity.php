<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Amenity extends Model
{
    use HasFactory;

    protected $table = 'amenities';
    protected $fillable = ['code', 'name'];

    // Nota: En el futuro, este modelo podría tener relaciones con RoomType
    // a través de una tabla pivot (room_type_amenity) para asociar amenities
    // con tipos de habitaciones.
}
