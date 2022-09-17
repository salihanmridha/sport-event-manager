<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\SportResource;
use App\Http\Resources\UserSearchResource;

class TeamMemberResource extends JsonResource
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
            'team_id' => $this->team_id,
            'user_id' => $this->user_id,
            'full_name' => trim(
                $this->team_member->first_name .
                    ' ' .
                    $this->team_member->last_name
            ),
            'avatar' => $this->team_member->avatar,
            'background_image' => $this->team_member->background_image,
            'roles' => isset($this->member_role) ? $this->member_role : [],
            'jersey_number' => $this->jersey_number,
            'player_role' => $this->player_role,
            'status' => $this->status,
            'weight' => $this->weight,
        ];
    }
}
