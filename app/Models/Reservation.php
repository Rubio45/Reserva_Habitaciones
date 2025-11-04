<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reservation extends Model
{
    use HasFactory;

    protected $table = 'reservations';
    protected $fillable = [
        'code','guest_id','status','adults','children',
        'currency','total_amount','paid_amount','check_in','check_out','channel'
    ];

    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }

    public function roomsLines()
    {
        // líneas por habitación / rango de fechas
        return $this->hasMany(ReservationRoom::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
