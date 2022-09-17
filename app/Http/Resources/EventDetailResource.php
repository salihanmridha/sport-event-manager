<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class EventDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);
        if (isset($data['sport']['media'])) {
            unset($data['sport']['media']);
        }
        if (isset($data['user_create']['media'])) {
            unset($data['user_create']['media']);
        }
        unset($data['media']);
        return array_merge($data, [
            'image' => $this->images(),
        ]);
    }
}
