<?php

namespace App\Models;

use App\Traits\UuidPrimaryKey;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Passport\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens, Notifiable, UuidPrimaryKey;




    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'streetAddress',
        'neighborhoodAddress', 'numberAddress',
        'phoneNumber', 'complementAddress', 'photo',
        'isAdmin', 'cpf'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
    }

    public function generateTokenAccess()
    {
        if (isset($this)) {

            $tokenAccess = $this->createToken(config('APP_NAME', 'SEL'))->accessToken;
        }

        return $tokenAccess;
    }
}
