<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Collection extends Model
{

    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'collections';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'idCollection';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'author',
        'cdd',
        'cdu',
        'isbn',
        'publisherCompany',
        'idCollectionType',
        'idCollectionCategory',
        'idAcquisition',
    ];

    /**
     * Get the acquisition that owns the Collection
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function acquisition(): BelongsTo
    {
        return $this->belongsTo(Acquisition::class, 'idAcquisition');
    }

    /**
     * Get the collectionType that owns the Collection
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function collectionType(): BelongsTo
    {
        return $this->belongsTo(CollectionType::class, 'idCollectionType');
    }

    /**
     * Get the collectionCategory that owns the Collection
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function collectionCategory(): BelongsTo
    {
        return $this->belongsTo(CollectionCategory::class, 'idCollectionCategory');
    }


    /**
     * Get all of the copies for the Collection
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function copies(): HasMany
    {
        return $this->hasMany(CollectionCopy::class, 'idCollection', 'idCollection');
    }


    public function scopeQuantityOfCopiesAvailable($query)
    {
        return $this->copies->where('isAvailable', true)->count();
    }
}
