<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Dashboard extends JsonResource
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
                'type' => 'dashboards',
                'id' => $this->id,
                'attributes' => [
                    'field_id' => $this->field_id,
                    'operation' => $this->operation,
                    'data' => $this->operationData()
                ],
            ],
            'links' => [
                'self' => url('/dashboards/' . $this->id)
            ]
        ];
    }
}
