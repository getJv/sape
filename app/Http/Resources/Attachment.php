<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ProjectStatus as ProjectStatusResource;

class Attachment extends JsonResource
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
            'data'  => [
                'type' => 'attachments',
                'id'   => $this->id,
                'attributes' => [
                    'projec_id' => $this->project_id,
                    'status_id' => $this->status_id,
                    'original_filename' => $this->original_filename,
                    'filename_at_disk' => $this->filename_at_disk,
                    'mime_type' => $this->mime_type,
                ]
            ],
            'links' => [
                'self' => url('/attachment/' . $this->id ),
                'status' => new ProjectStatusResource($this->status)
            ]
        ];
    }
}
