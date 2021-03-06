<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProjectFieldCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data'                 => $this->collection,
            'total_project_fields'       => $this->count(),
            'links'      => [
                'self' => url('/project-fields/')
            ],

        ];
    }
}
