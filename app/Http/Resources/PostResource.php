<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class PostResource extends JsonResource
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
            'content' => $this->content,
            'team_id' => $this->team_id,
            'creator_id' => $this->creator_id,
            'creator_type' => $this->creator_type,
            'user_post' =>
                isset($this->user_post) && $this->creator_type == 'user'
                    ? $this->user_post
                    : null,
            'team_post' =>
                isset($this->team_post) && $this->creator_type == 'team'
                    ? $this->team_post
                    : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'dataType' => $this->dataType,
        ];
    }
}
