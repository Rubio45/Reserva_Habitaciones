<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Room extends Model
{
    use HasFactory;

    protected $table = 'rooms';
    protected $fillable = ['room_type_id','room_number','floor','status'];

    public function type()
    {
        return $this->belongsTo(RoomType::class, 'room_type_id');
    }

    public function reservationRooms()
    {
        return $this->hasMany(ReservationRoom::class);
    }
}
