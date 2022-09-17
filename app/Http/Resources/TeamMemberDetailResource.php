<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\SportResource;
use App\Http\Resources\UserSearchResource;
use App\Http\Resources\TeamMemberResource;

class TeamMemberDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = new TeamMemberResource($this);
        return array_merge(
            [
                'gender' => $this->team_member->gender,
                'birth_date' => $this->team_member->birth_date,
                'phone' => $this->team_member->phone,
                'phone_code' => $this->team_member->phone_code,
                'email' => $this->team_member->email,
                'ec_fullname' => $this->team_member->ec_fullname,
                'ec_relationship' => $this->team_member->ec_relationship,
                'ec_main_pcode' => $this->team_member->ec_main_pcode,
                'ec_main_pnum' => $this->team_member->ec_main_pnum,
                'ec_alt_pnum' => $this->team_member->ec_alt_pnum,
                'ec_email' => $this->team_member->ec_email,
            ],
            json_decode(json_encode($data), true)
        );
    }
}
