<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Provider;
use Illuminate\Database\Eloquent\SoftDeletes;

class NaturalPerson extends Provider
{

    use SoftDeletes;

    protected $table = 'natural_persons';

    protected $primaryKey = 'idNaturalPerson';

    protected $fillable = [
        'idProvider', 'cpf',  

    ];

    public function provider()
    {
        return $this->belongsTo('App\Models\Provider', 'idProvider');
    }
}
