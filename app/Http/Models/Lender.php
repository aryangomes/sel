<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lender extends Model
{
    use SoftDeletes;

    protected $table = 'lenders';

    protected $primaryKey = 'idLender';


    protected $fillable = [
        'name', 'email',  'streetAddress',
        'neighborhoodAddress', 'numberAddress',
        'phoneNumber', 'cellNumber', 'complementAddress' ,
        'site',   

    ];
}
