<?php

namespace App\Models\Sport;

use App\Enums\MissingImagesEnum;
use Illuminate\Http\UploadedFile;

trait SportAttributes
{
    /**
     * Set sport icon.
     *
     * @param UploadedFile|mixed $value
     */
    public function setAvatarAttribute($value)
    {
        $this->clearMediaCollection('sport');

        if ($value !== null) {
            $this->addMedia($value)->toMediaCollection('sport', 'sport');
        }
    }

    /**
     * Get link to sport icon.
     */
    public function getIconAttribute(): string
    {
        $media = $this->getFirstMedia('sport');
        return isset($media)
            ? $media->getFullUrl()
            : (missing_image(MissingImagesEnum::AVATAR_MALE);
    }
}
