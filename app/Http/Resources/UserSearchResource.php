<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserSearchResource extends JsonResource
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
        $data['avatar'] = $this->getAvatarUrl();
        $data['background_image'] = $this->getBGUrl();
        $data['currency'] = $this->currency;
        $data['has_password'] =!empty($this?->password);
        $data['count_follower'] = $this->count_follower;
        $data['count_following'] = $this->count_following;
        $data['count_team'] = $this->member_teams_count;
        $data['sports'] = $this->sports;
        unset($data['member_teams_count']);
        unset($data['media']);
        return $data;
    }
}
