<?php

namespace App\Exceptions;

use Exception;

class ProjectStatusNotFoundException extends Exception
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
                'code'  => 404,
                'title' => 'O status de projeto não existe ou foi excluído',
                'detail' => 'O sistema tentou utilizar status de projeto inexistente ou que já foi excluído'
            ]

        ], 404);
    }
}
