<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\TeamMemberResource;

const LIST_STATUS = ['active', 'inactive'];

class TeamRosterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    public function toArray($request)
    {
        // $data = TeamMemberResource::collection($this);
        dump($this);

        // $data = [
        //     'active' => [],
        //     'inactive' => [],
        //     'none_player' => [],
        // ]
        // if($this->status == LIST_STATUS[0] && )
        // $data = parent::toArray($request);
        // $data['sport'] = $this->sport;
        // $data['creator'] = $this->creator;
        // return $data;
    }
}
