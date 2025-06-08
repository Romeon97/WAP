<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OriginalMeasurement extends Model
{
    protected $table = 'original_measurement';

    protected $fillable = [
        'corrected_measurement',
        'missing_field',
        'invalid_temperature',
    ];

    public $timestamps = false;
}
