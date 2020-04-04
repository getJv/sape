<?php

namespace App\Exceptions;

use Exception;

class ProjectNotFoundException extends Exception
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
                'title' => 'O projeto não existe ou foi excluído',
                'detail' => 'O sistema tentou utilizar um projeto inexistente ou que já foi excluído'
            ]

        ], 404);
    }
}
