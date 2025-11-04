<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HotelSetting extends Model
{
    use HasFactory;

    protected $table = 'hotel_settings';
    public $timestamps = true;

    // si tu PK es tinyint y no auto-incrementa, ajusta:
    // public $incrementing = false;
    // protected $keyType = 'int';

    protected $fillable = [
        'code','name','legal_name','country_code','timezone',
        'address_line1','address_line2','city','state','postal_code',
        'phone','email','currency','notes'
    ];
}
