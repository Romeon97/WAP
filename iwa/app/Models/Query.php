<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Query extends Model
{
    protected $table = 'query';
    protected $fillable = [
        'contract_id',
        'omschrijving',
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id');
    }

    //relatie naar 'criterium_group'
    public function groups()
    {
        // 'query' is de FK-kolom in 'criterium_group'
        return $this->hasMany(CriteriumGroup::class, 'query');
    }
}
