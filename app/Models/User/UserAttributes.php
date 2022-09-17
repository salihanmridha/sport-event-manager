<?php

namespace App\Models\User;

use App\Enums\MissingImagesEnum;
use App\Enums\UserGendersEnum;
use App\Enums\UserRolesEnum;
use Illuminate\Http\UploadedFile;

trait UserAttributes
{
    /**
     * Set user avatar.
     *
     * @param UploadedFile|mixed $value
     */
    public function setAvatarAttribute($value)
    {
        $this->clearMediaCollection('avatar');

        if ($value !== null) {
            $this->addMedia($value)->toMediaCollection('avatar', 'avatar');
        }
    }

    /**
     * Get link to user avatar.
     */
    public function getAvatarAttribute(): string
    {
        $media = $this->getFirstMedia('avatar');
        return isset($media)
            ? $media->getFullUrl()
            : (
                UserGendersEnum::MALE()->is($this->gender) || $this->gender === null
                ? missing_image(MissingImagesEnum::AVATAR_MALE)
                : missing_image(MissingImagesEnum::AVATAR_FEMALE)
            );
    }

    public function getIsTooManyEmailVerificationAttemptsAttribute(): bool
    {
        return $this->email_verification_attempts = 3;
    }

    public function hasRole(UserRolesEnum $role): bool
    {
        return $this->role === $role->getValue();
    }

    public function assignRole(UserRolesEnum $role)
    {
        $this->role = $role->getValue();
    }
}
