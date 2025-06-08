<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $timestamps = false;

    protected $fillable = [
        'first_name',
        'prefix',
        'name',
        'initials',
        'email',
        'employee_code',
        'password',
        'user_role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'user_role' => 'integer',
        ];
    }

    /**
     * Relatie met de userroles-tabel.
     */
    public function role()
    {
        return $this->belongsTo(UserRole::class, 'user_role', 'id');
    }
}
