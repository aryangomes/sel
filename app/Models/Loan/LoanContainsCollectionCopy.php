<?php

namespace App\Models\Loan;

use App\Models\CollectionCopy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
    ];

    /**
     * The loan that belong to the LoanContainsCollectionCopy
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class, 'idLoan');
    }

    /**
     * The collectionCopy that belong to the LoanContainsCollectionCopy
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function collectionCopies(): HasMany
    {
        return $this->hasMany(CollectionCopy::class,  'idCollectionCopy');
    }

    /**
     * Get the collectionCopy associated with the LoanContainsCollectionCopy
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function collectionCopy(): HasOne
    {
        return $this->hasOne(CollectionCopy::class, 'idCollectionCopy');
    }
}
