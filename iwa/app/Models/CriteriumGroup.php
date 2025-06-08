<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CriteriumGroup extends Model
{
    protected $table = 'criterium_group';

    protected $fillable = [
        'query',
        'type',
        'group_level',
        'operator',
        // ...
    ];

    // Relatie naar 'query'
    public function queryObj()
    {
        // De kolom 'query' in 'criterium_group' verwijst naar 'query.id'
        return $this->belongsTo(Query::class, 'query');
    }

    // Relatie naar alle 'criterium' records die horen bij deze group
    public function criteria()
    {
        // 'group' is de FK in de 'criterium'-tabel
        return $this->hasMany(Criterium::class, 'group');
    }
}
