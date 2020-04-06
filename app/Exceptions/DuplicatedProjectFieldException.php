<?php

namespace App\Exceptions;

use Exception;

class DuplicatedProjectFieldException extends Exception
{
    public function render($request)
    {
        return response()->json([
            'errors' => [
                'code'  => 403,
                'title' => 'Este campo já foi vinculado ao projeto',
                'detail' => 'Não é possível vincular o compo ao projeto mais de uma vez'
            ]
        ], 403);
    }
}
