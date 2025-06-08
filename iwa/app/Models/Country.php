<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    public $timestamps = false;
    protected $table = 'country';
    protected $primaryKey = 'country_code';
    public $incrementing = false;
    protected $keyType = 'string';
}
