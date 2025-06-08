<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Criterium extends Model
{
    protected $table = 'criterium';
    protected $fillable = [
        'group',
        'operator',
        'int_value',
        'string_value',
        'float_value',
        'value_type',
        'comparison',
        // ...
    ];

    // De relatie naar 'criterium_group'
    public function groupObj()
    {
        // Kolom 'group' in 'criterium' verwijst naar 'criterium_group.id'
        return $this->belongsTo(CriteriumGroup::class, 'group');
    }
}
