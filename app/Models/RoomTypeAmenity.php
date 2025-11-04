<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class RoomTypeAmenity extends Pivot
{
    protected $table = 'room_type_amenity';
    public $timestamps = false;

    protected $fillable = ['room_type_id','amenity_id'];
}
