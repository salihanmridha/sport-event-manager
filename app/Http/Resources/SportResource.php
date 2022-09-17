<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => isset($this->code) ? $this->code : null,
            'description' => $this->description,
            'max_player_per_team' => $this->max_player_per_team,
            'min_player_per_team' => $this->min_player_per_team,
            'is_require_choose_role' => $this->is_require_choose_role,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'image' => $this->getIcon(),
        ];
    }
}
