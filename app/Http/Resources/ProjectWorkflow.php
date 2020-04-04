<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Project as ProjectResource;
use App\Http\Resources\ProjectStatus as ProjectStatusResource;

class ProjectWorkflow extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => [
                'type' => 'project-workflows',
                'id' => $this->id,
                'attributes' => [
                    'order'      => $this->order,
                    'project_id' => $this->project_id,
                    'old_status_id' => $this->old_status_id,
                    'new_status_id' => $this->new_status_id,
                ],

            ],
            'links' => [
                'self' => url('/project-workflows/' . $this->id),
                'project' => new ProjectResource($this->project),
                'old_status' => new ProjectStatusResource($this->old_status),
                'new_status' => new ProjectStatusResource($this->new_status),
            ]
        ];
    }
}
