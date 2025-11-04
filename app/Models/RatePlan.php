<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RatePlan extends Model
{
    use HasFactory;

    protected $table = 'rate_plans';
    protected $fillable = [
        'code','name','description','cancellation_policy',
        'meal_plan','is_refundable','is_active'
    ];

    public function prices()
    {
        return $this->hasMany(RatePlanPrice::class);
    }

    public function reservationRooms()
    {
        return $this->hasMany(ReservationRoom::class);
    }
}
