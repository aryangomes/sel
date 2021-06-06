<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Loan extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'loans';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'idLoan';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'loansIdentifier',
        'returnDate',
        'expectedReturnDate',
        'observation',
        'idOperatorUser',
        'idBorrowerUser',
    ];

    protected $dates = [
        'returnDate',
        'expectedReturnDate',
    ];

    /**
     * Get the operatorUser associated with the Loan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function operatorUser(): HasOne
    {
        return $this->hasOne(User::class, 'idOperatorUser');
    }

    /**
     * Get the borrowerUser associated with the Loan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function borrowerUser(): HasOne
    {
        return $this->hasOne(User::class, 'idBorrowerUser');
    }
}
