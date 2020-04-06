<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Project as ProjectResource;
use App\Http\Resources\Field as FieldResource;

class ProjectField extends JsonResource
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
                'type' => 'project-fields',
                'id' => $this->id,
                'attributes' => [
                    'project_id' => $this->project_id,
                    'field_id'   => $this->field_id,
                    'active'     => $this->active,
                ],

            ],
            'links' => [
                'self' => url('/project-fields/' . $this->id),
                'project' => new ProjectResource($this->project),
                'field' => new FieldResource($this->field)
            ]
        ];
    }
}
