<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Guest extends Model
{
    use HasFactory;

    protected $table = 'guests';
    
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'document_type',
        'document_number',
        'country_code',
        'notes',
    ];

    protected $casts = [
        'notes' => 'string',
    ];

    /**
     * Accessor para obtener el nombre completo
     * 
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    /**
     * Nota: En el futuro, este modelo se relacionará con:
     * - reservations (hasMany) - Reservas realizadas por este huésped
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
