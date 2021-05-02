<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Provider;
use Illuminate\Database\Eloquent\SoftDeletes;

class JuridicPerson extends Provider
{
    use SoftDeletes;

    protected $table = 'juridic_persons';

    protected $primaryKey = 'idJuridicPerson';

    protected $fillable = [
        'idProvider', 'cnpj',  

    ];

    public function provider()
    {
        return $this->belongsTo('App\Models\Provider', 'idProvider');
    }
}
