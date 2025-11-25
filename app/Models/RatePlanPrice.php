<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RatePlanPrice extends Model
{
    use HasFactory;

    protected $table = 'rate_plan_prices';

    protected $fillable = [
        'rate_plan_id',
        'room_type_id',
        'date_from',
        'date_to',
        'occupancy',
        'price',
        'extra_adult',
        'extra_child',
        'currency',
    ];

    protected $casts = [
        'date_from' => 'date',
        'date_to' => 'date',
        'occupancy' => 'integer',
        'price' => 'decimal:2',
        'extra_adult' => 'decimal:2',
        'extra_child' => 'decimal:2',
    ];

    public function ratePlan()
    {
        return $this->belongsTo(RatePlan::class);
    }

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }

    /**
     * Scope para filtrar por fecha específica
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param Carbon|string $date
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForDate($query, $date)
    {
        $date = $date instanceof Carbon ? $date : Carbon::parse($date);

        return $query->where('date_from', '<=', $date)
            ->where('date_to', '>=', $date);
    }

    /**
     * Scope para filtrar por rango de fechas
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param Carbon|string $from
     * @param Carbon|string $to
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForRange($query, $from, $to)
    {
        $from = $from instanceof Carbon ? $from : Carbon::parse($from);
        $to = $to instanceof Carbon ? $to : Carbon::parse($to);

        return $query->where(function ($q) use ($from, $to) {
            $q->whereBetween('date_from', [$from, $to])
                ->orWhereBetween('date_to', [$from, $to])
                ->orWhere(function ($q2) use ($from, $to) {
                    $q2->where('date_from', '<=', $from)
                        ->where('date_to', '>=', $to);
                });
        });
    }
}
