<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Acquisition extends Model
{

    use SoftDeletes;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'idAcquisition';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['
    price', 'quantity', 'idLender', 'idProvider', 'idAcquisitionType'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'acquisitions';

    public function lender()
    {
        return $this->belongsTo('App\Models\Lender', 'idLender');
    }

    public function provider()
    {
        return $this->belongsTo('App\Models\Provider', 'idProvider');
    }

    public function acquisitionType()
    {
        return $this->belongsTo('App\Models\AcquisitionType', 'idAcquisitionType');
    }
}
