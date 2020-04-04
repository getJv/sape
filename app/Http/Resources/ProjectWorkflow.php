<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
                    'project_id' => $this->project_id,
                    'order'      => $this->order,
                    'old_status' => $this->old_status,
                    'new_status' => $this->new_status,
                ],

            ],
            'links' => [
                'self' => url('/project-workflows/' . $this->id)
            ]
        ];
    }
}
