<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $table = 'subscriptions';

    protected $fillable = [
        'company',
        'type',
        'start_date',
        'end_date',
        'price',
        'identifier',
        'notes',
        'token'
    ];

    public $timestamps = false;

    public function companyRelation()
    {
        return $this->belongsTo(Company::class, 'company', 'id');
    }

    public function subscriptionType()
    {
        return $this->belongsTo(SubscriptionType::class, 'type');
    }
}
