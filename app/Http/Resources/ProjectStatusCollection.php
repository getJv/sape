<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\AttachmentCollection;

class ProjectStatusCollection extends ResourceCollection
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
            'project_statuses_count'        => $this->count(),
            'links'      => [
                'self' => url('/project-statuses')
            ],

        ];
    }
}
