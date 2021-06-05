<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoanContainsCollectionCopy extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'loan_contains_collection_copies';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'idLoanContainsCollectionCopy';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'idLoan',
        'idCollectionCopy',
        'quantity',
    ];

    /**
     * The loan that belong to the LoanContainsCollectionCopy
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function loan(): BelongsToMany
    {
        return $this->belongsToMany(Loan::class, 'loans', 'idLoan');
    }

    /**
     * The collectionCopy that belong to the LoanContainsCollectionCopy
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function collectionCopy(): BelongsToMany
    {
        return $this->belongsToMany(CollectionCopy::class, 'collection_copies', 'idCollectionCopy');
    }
}
