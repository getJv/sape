<?php

namespace App\Exceptions;

use Exception;

class ValidationErrorException extends Exception
{
    /**
     * Render the exception as an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return response()->json([
            'errors' => [
                'code'  => 422,
                'title' => 'Erro de Validação',
                'detail' => 'A requisição esta mal formatada ou não possui parâmetros obrigatórios',
                'meta' => json_decode($this->getMessage())
            ]
        ], 422);
    }
}
