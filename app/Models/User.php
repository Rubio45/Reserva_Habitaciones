<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password_hash',
        'is_active',
    ];

    protected $hidden = ['password_hash'];

    // Si tu login usa 'password', puedes crear un accessor/mutator,
    // pero como tu columna es password_hash nos quedamos así.

    // === Roles ===
    // Si tu esquema tiene pivot (común) 'role_user' con user_id, role_id:
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user')
            ->withTimestamps();
    }

    // Devuelve el campo correcto para Auth
public function getAuthPassword()
{
    return $this->password_hash;
}


    // Si en cambio users tiene role_id directo:
    // public function role() { return $this->belongsTo(Role::class); }
}
