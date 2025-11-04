<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RoomType extends Model
{
    use HasFactory;

    protected $table = 'room_types';
    protected $fillable = [
        'code','description','base_occupancy','max_occupancy',
        'bed_config','area_m2','is_active'
    ];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'room_type_amenity')
            ->withTimestamps();
    }

    public function ratePlanPrices()
    {
        return $this->hasMany(RatePlanPrice::class);
    }
}
