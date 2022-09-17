<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\SportResource;
use App\Http\Resources\UserSearchResource;

class ListRequestResource extends JsonResource
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
        $parsed = [];
        $parsed['id'] = isset($data['pivot']['id'])
            ? $data['pivot']['id']
            : null;
        $parsed['team_id'] = isset($data['pivot']['team_id'])
            ? $data['pivot']['team_id']
            : null;
        $parsed['user_id'] = $data['id'];
        $parsed['full_name'] = trim(
            $data['first_name'] . ' ' . $data['last_name']
        );
        $parsed['sport'] = $data['sports'];
        $parsed['avatar'] = $data['avatar'];
        $parsed['background_image'] = $data['background_image'];
        $parsed['member_count'] = $data['member_teams_count'];
        $parsed['created_at'] = isset($data['pivot']['created_at'])
            ? date('Y-m-d H:i:s', strtotime($data['pivot']['created_at']))
            : null;
        return array_merge($parsed, [
            'count_follower' => $this->count_follower,
        ]);
    }

    protected function getUrlArr($arr)
    {
        return isset($arr[0]['original_url']) ? $arr[0]['original_url'] : null;
    }
}
