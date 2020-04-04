<?php

namespace App\Exceptions;

use Exception;

class DuplicatedWorkflowStepExcetion extends Exception
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
                'code'  => 403,
                'title' => 'Já existe um passo de workflow com esta origem e destino.',
                'detail' => 'Não é possivel adicionar a mesma origem e destido duas vezes.'
            ]
        ], 403);
    }
}
