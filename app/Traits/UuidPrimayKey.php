<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

trait UuidPrimaryKey
{
    protected static function bootUuid()
    {
        
        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
        
    }

    public function getIncrementing()
    {
        return false;
    }

    public function getKeyType()
    {
        return 'string';
    }
}
