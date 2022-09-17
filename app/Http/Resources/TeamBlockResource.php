<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TeamBlockResource extends JsonResource
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
            'team_id' => $this->team_id,
            'user_id' => $this->user_id,
            'full_name' => trim(
                $this->user->first_name .
                    ' ' .
                $this->user->last_name
            ),
            'avatar' => $this->user->avatar
        ];
    }
}
