<?php

namespace App\Http\Models\Utils;

use Illuminate\Database\Eloquent\Model;

class HttpStatusCodes extends Model
{
    static public $SUCESSO = 200;

    static public $CRIADO = 201;
    
    static public $SEM_CONTEUDO = 204;

    static public $ERRO = 400;

    static public $NAO_AUTENTICADO = 401;

    static public $PROIBIDO = 403;

    static public $NAO_ENCONTRADO = 404;

    static public $ENTIDADE_NAO_PROCESSADA = 422;

    static public $ERRO_INTERNO_SERVIDOR = 500;
}
