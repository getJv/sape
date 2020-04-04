<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProjectWorkflowCollection extends ResourceCollection
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
            'workflow_steps'       => $this->count(),
            'links'      => [
                'self' => url('/project-workflows/project/' . $this->collection->first()->project->id)
            ],

        ];
    }
}
