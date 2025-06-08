<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'street',
        'number',
        'zip_code',
        'city',
        'country',
        'email',
    ];

    public $timestamps = false;

    // Relatie naar subscriptions
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'company');
    }


}
