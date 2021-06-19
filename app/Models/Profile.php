<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Profile extends Model
{
    use SoftDeletes;

    protected $table = 'profiles';

    protected $primaryKey = 'idProfile';

    protected $fillable = [
        'profile'

    ];


    /**
     * Get the user that owns the Provider
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'idProfile', 'idProfile');
    }
}
