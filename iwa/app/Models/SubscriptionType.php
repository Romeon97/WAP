<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionType extends Model
{
    use HasFactory;

    protected $table = 'subscription_types';

    protected $fillable = [
        'name',
        'description',
        'nr_stations',
        'frequency_in_hours',
        'frequency_in_days',
        'continuous',
        'price_per_station',
        'valid_through'
    ];

    // Relatie naar subscriptions
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'type');
    }
}
