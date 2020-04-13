<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ProjectWorkflow as ProjectWorkflowResource;

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
                    'owner_id' => $this->owner_id,
                    'project_workflow_id' => $this->project_workflow_id,
                    'active' => $this->active,
                ],

            ],
            'links' => [
                'self' => url('/project-events/' . $this->id),
            ]
        ];
    }
}
