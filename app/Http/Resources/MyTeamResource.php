<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\SportResource;
use App\Http\Resources\UserSearchResource;

class MyTeamResource extends JsonResource
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
            'gender' => $this->gender,
            'team_avatar_image' => $this->team_avatar_image,
            'team_background' => $this->team_background,
            'season' => $this->season,
            'members_count' => $this->members_count,
            'age_group' => $this->age_group,
            'level_id' => $this->level_id,
            'sport_id' => $this->sport_id,
            'count_notification' => null,
        ];
    }
}
