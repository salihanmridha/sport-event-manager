<?php

namespace App\Http\Resources\Profiles;

use App\Enums\GenderEnum;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CountryResource;
use App\Http\Resources\UserResource;

class ProfileResource extends UserResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array|Arrayable|\JsonSerializable
     */
    public function toArray($request): array|\JsonSerializable|Arrayable
    {
        $data = parent::toArray($request);
        return array_merge($data, [
            'id' => $this?->id,
            'bio' => $this?->bio,
            'gender' => $this?->gender ? GenderEnum::from($this->gender) : '',
            'country' => new CountryResource($this->country),
            'first_name' => $this?->first_name,
            'last_name' => $this?->last_name,
            'full_name' => $this->name,
            'email' => $this?->email,
            'phone' => $this?->phone,
            'birthdate' => $this->birth_date,
            'is_email_verified' => (bool) $this?->email_verified_at,
            'has_password' => !empty($this?->password),
            'count_follower' => $this->count_follower,
            'count_following' => $this->count_following,
            'count_team' => $this->member_teams_count,
        ]);
    }
}
