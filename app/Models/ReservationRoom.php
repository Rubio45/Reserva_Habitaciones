<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ReservationRoom extends Pivot
{
    protected $table = 'reservation_rooms';
    public $timestamps = false;

    protected $fillable = [
        'reservation_id','room_type_id','room_id','rate_plan_id',
        'nightly_price','date_from','date_to'
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function roomType()
    {
        return $this->belongsTo(RoomType::class, 'room_type_id');
    }

    public function ratePlan()
    {
        return $this->belongsTo(RatePlan::class);
    }
}
