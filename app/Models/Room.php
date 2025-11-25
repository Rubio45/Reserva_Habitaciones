<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Room extends Model
{
    use HasFactory;

    protected $table = 'rooms';

    protected $fillable = [
        'room_type_id',
        'room_number',
        'floor',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function roomType()
    {
        return $this->belongsTo(RoomType::class, 'room_type_id');
    }

    public function reservationRooms()
    {
        return $this->hasMany(ReservationRoom::class);
    }

    // Scopes útiles para filtrar por estado
    // Ejemplo de uso: Room::available()->get()
    public function scopeAvailable($query)
    {
        return $query->where('status', 'AVAILABLE');
    }

    // Ejemplo de uso: Room::byStatus('CLEANING')->get()
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }
}
