<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Provider extends Model
{
    use SoftDeletes;

    protected $table = 'providers';

    protected $primaryKey = 'idProvider';

    protected $fillable = [
        'name', 'email',  'streetAddress',
        'neighborhoodAddress', 'numberAddress',
        'phoneNumber', 'cellNumber', 'complementAddress'  

    ];

    public function naturalPerson()
    {
        return $this->hasOne('App\Models\NaturalPerson', 'idProvider');
    }

    public function juridicPerson()
    {
        return $this->hasOne('App\Models\JuridicPerson', 'idProvider');
    }
}
