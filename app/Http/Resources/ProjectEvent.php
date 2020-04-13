<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Project as ProjectResource;
use App\Http\Resources\ProjectStatus as ProjectStatusResource;

class ProjectEvent extends JsonResource
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
                'type' => 'project-events',
                'id' => $this->id,
                'attributes' => [
                    'name' => $this->name,
                    'description' => $this->description,
                    'project_status_id' => $this->project_status_id,
                    'project_id' => $this->project_id,
                    'active' => $this->active,
                ],

            ],
            'links' => [
                'self' => url('/project-events/' . $this->id),
                'project' => new ProjectResource($this->project),
                'status' => new ProjectStatusResource($this->status),
            ]
        ];
    }
}
