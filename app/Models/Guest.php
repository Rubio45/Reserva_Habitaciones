<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Guest extends Model
{
    use HasFactory;

    protected $table = 'guests';
    protected $fillable = [
        'first_name','last_name','email','phone',
        'document_type','document_number','country_code','notes'
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
