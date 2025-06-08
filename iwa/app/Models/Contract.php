<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $table = 'contract';

    protected $fillable = [
        'identifier',
        'company_id',
        'omschrijving',
        'start_datum',
        'eind_datum',
        'url'
    ];

    // Een contract heeft meerdere queries
    public function queries()
    {
        return $this->hasMany(Query::class, 'contract_id');
    }
}
