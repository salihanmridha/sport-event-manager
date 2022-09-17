<?php

namespace App\Models\Announcement;

use App\Enums\MissingImagesEnum;
use Illuminate\Http\UploadedFile;

trait AnnouncementAttributes
{
    /**
     * Set sport icon.
     *
     * @param UploadedFile|mixed $value
     */
    public function setAvatarAttribute($value)
    {
        $this->clearMediaCollection('announcement');

        if ($value !== null) {
            $this->addMedia($value)->toMediaCollection('sport', 'sport');
        }
    }

    /**
     * Get link to sport icon.
     */
    public function getIconAttribute(): string
    {
        $media = $this->getFirstMedia('announcement');
        return isset($media)
            ? $media->getFullUrl()
            : (missing_image(MissingImagesEnum::AVATAR_MALE));
    }
}
