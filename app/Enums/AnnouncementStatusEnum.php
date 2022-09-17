<?php

namespace App\Enums;
use Spatie\Enum\Laravel\Enum;

/**
 * Class PostTypeEnum
 * @package App\Enums
 *
 * @method static self publish()
 * @method static self unpublish()
 */
final class AnnouncementStatusEnum extends Enum
{
    protected static function values(): array
    {
        return [
            'publish' => 'publish',
            'unpublish' => 'unpublish',
        ];
    }
}
