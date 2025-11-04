<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RatePlanPrice extends Model
{
    use HasFactory;

    protected $table = 'rate_plan_prices';
    protected $fillable = [
        'rate_plan_id','room_type_id','date_from','date_to',
        'occupancy','price','extra_adult','extra_child','currency'
    ];

    public function ratePlan()
    {
        return $this->belongsTo(RatePlan::class);
    }

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }
}
