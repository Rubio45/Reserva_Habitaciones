<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RatePlan extends Model
{
    use HasFactory;

    protected $table = 'rate_plans';
    
    protected $fillable = [
        'code',
        'name',
        'description',
        'cancellation_policy',
        'meal_plan',
        'is_refundable',
        'is_active',
    ];

    protected $casts = [
        'is_refundable' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Nota: En el futuro, este modelo se relacionará con:
    // - rate_plan_prices (1:N) - Precios del plan de tarifa por tipo de habitación y fechas
    // - reservation_rooms (N:1) - Habitaciones de reserva que usan este plan de tarifa
    public function prices()
    {
        return $this->hasMany(RatePlanPrice::class);
    }

    public function reservationRooms()
    {
        return $this->hasMany(ReservationRoom::class);
    }
}
