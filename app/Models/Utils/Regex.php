<?php

namespace App\Models\Utils;

use Illuminate\Database\Eloquent\Model;

class Regex extends Model
{
    public const CPF = '[0-9]{3}[0-9]{3}[0-9]{3}[0-9]{2}';
}
