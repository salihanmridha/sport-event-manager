<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\SportResource;
use App\Http\Resources\UserSearchResource;

class TeamResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // print_r()';'
        $this->sport = new SportResource($this->sport);
        $this->creator = new UserSearchResource($this->creator);
        $data = parent::toArray($request);
        if (isset($data['user_create']['media'])) {
            unset($data['user_create']['media']);
        }
        $data['sport'] = $this->sport;
        $data['creator'] = $this->creator;
        $data['roster'] = !empty($this->teamMember) ? $this->teamMember : null;
        unset($data['media']);
        unset($data['team_member']);
        return array_merge($data, [
            'count_follower' => $this->count_follower,
            'count_match' => $this->count_match,
            'count_member' => $this->members_count,
        ]);
    }

    protected function getUrlArr($arr)
    {
        return isset($arr[0]['original_url']) ? $arr[0]['original_url'] : null;
    }
}
