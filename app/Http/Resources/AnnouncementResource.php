<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class AnnouncementResource extends JsonResource
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
        $data['image'] = count($data['background_image'])
            ? $this->customImage($data['background_image'])
            : [];
        if (isset($data['creator']['media'])) {
            unset($data['creator']['media']);
        }
        unset($data['media']);
        unset($data['background_image']);
        return $data;
    }

    protected function customImage($listImage)
    {
        return Arr::map($listImage, function ($value, $key) {
            return [
                'id' => $value['id'],
                'name' => $value['name'],
                'url' => $value['url'],
            ];
        });
    }
}
