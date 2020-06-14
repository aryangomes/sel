<?php

namespace App\Models;

use App\Events\LogoutUserEvent;
use App\Http\Models\Utils\LogFormatter;
use App\Traits\UuidPrimaryKey;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, UuidPrimaryKey;

    protected $table = 'users';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'streetAddress',
        'neighborhoodAddress', 'numberAddress',
        'phoneNumber','cellNumber', 'complementAddress', 'photo',
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
         
        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Str::uuid();
            
        });
    }

    public function generateTokenAccess()
    {
        if (isset($this)) {

            $tokenAccess = $this->createToken(config('APP_NAME', 'SEL'))->accessToken;
        }

        return $tokenAccess;
    }

    public function setPasswordAttribute($newPassword)
    {
        $newPasswordIsSet = isset($newPassword);

        $oldPasswordIsSet = isset($this->password);

        $oldAndNewPasswordAreSet =  $oldPasswordIsSet && $newPasswordIsSet;

        if (!($oldAndNewPasswordAreSet)) {
            $this->attributes['password'] = $this->getDefaultPasswordUserNotAdmin();
        } else {
            $this->attributes['password'] =
                ($newPasswordIsSet) ? bcrypt($newPassword) : $this->getDefaultPasswordUserNotAdmin();
        }
    }

    private function getDefaultPasswordUserNotAdmin()
    {
        $defaultPassword = bcrypt((env('DEFAULT_PASSWORD_NOT_ADMIN')));

        return $defaultPassword;
    }

    public function logout()
    {
        $tokenAccessWasRevoken= false;
        try{
            $tokenAccess = $this->token();

            $tokenAccessWasRevoken = $tokenAccess->revoke();
     
        }catch(\Exception $exception){
            Log::error(LogFormatter::formatTextLog(['Message'=>$exception->getMessage()]));
        }
     
        if( $tokenAccessWasRevoken){
            event(new LogoutUserEvent($this));

        }

        return $tokenAccessWasRevoken;

    }
}
