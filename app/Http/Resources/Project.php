<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Project extends JsonResource
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
                'type' => 'projects',
                'id' => $this->id,
                'attributes' => [
                    'name' => $this->name,
                    'description' => $this->description,
                    'active' => $this->active,
                ],

            ],
            'links' => [
                'self' => url('/projects/' . $this->id),
                'attachments' => new AttachmentCollection($this->attachments)
            ]
        ];
    }
}
