<?php

namespace App\Models\Loan;

use App\Events\Loan\CreatedLoanEvent;
use App\Events\Loan\CreatingLoanEvent;
use App\Traits\Loan\StatusLoanTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Loan extends Model
{
    use SoftDeletes, StatusLoanTrait;

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
        'status',
        'idOperatorUser',
        'idBorrowerUser',
    ];

    protected $dates = [
        'returnDate',
        'expectedReturnDate',
    ];


    protected $dispatchesEvents = [
        'created' => CreatedLoanEvent::class
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

    /**
     * Get all of the colllectionCopies for the Loan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function containCopies(): HasMany
    {
        return $this->hasMany(LoanContainsCollectionCopy::class, 'idLoan');
    }
}
