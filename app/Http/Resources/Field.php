<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Field extends JsonResource
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
                'type' => 'fields',
                'id' => $this->id,
                'attributes' => [
                    'name' => $this->name,
                    'type' => $this->type,
                    'description' => $this->description,
                    'min' => $this->min,
                    'max' => $this->max,
                    'mask' => $this->mask,
                    'items' => $this->items,
                    'required' => $this->required,
                    'active' => $this->active,
                ],
            ],
            'links' => [
                'self' => url('/fields/' . $this->id)
            ]
        ];
    }
}
