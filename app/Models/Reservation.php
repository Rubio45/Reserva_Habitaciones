<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reservation extends Model
{
    use HasFactory;

    protected $table = 'reservations';
    
    protected $fillable = [
        'code',
        'guest_id',
        'status',
        'channel',
        'check_in',
        'check_out',
        'adults',
        'children',
        'currency',
        'total_amount',
        'paid_amount',
        'notes',
    ];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
        'adults' => 'integer',
        'children' => 'integer',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
    ];

    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }

    public function rooms()
    {
        return $this->hasMany(ReservationRoom::class);
    }

    // Alias para compatibilidad con código existente
    public function roomsLines()
    {
        return $this->rooms();
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Scope para filtrar por rango de fechas (check_in)
     */
    public function scopeBetweenDates($query, $from, $to)
    {
        return $query->whereBetween('check_in', [$from, $to]);
    }

    /**
     * Scope para filtrar por estado
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
