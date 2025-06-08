<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    protected $table = 'userroles';

    public $timestamps = false;

    protected $fillable = ['role'];
}
