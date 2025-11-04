<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Amenity extends Model
{
    use HasFactory;

    protected $table = 'amenities';
    protected $fillable = ['code', 'name'];

    public function roomTypes()
    {
        return $this->belongsToMany(RoomType::class, 'room_type_amenity')
            ->withTimestamps();
    }
}
