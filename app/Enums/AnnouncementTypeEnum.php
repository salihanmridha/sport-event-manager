<?php

namespace App\Enums;
use Spatie\Enum\Laravel\Enum;

/**
 * Class PostTypeEnum
 * @package App\Enums
 *
 * @method static self news()
 * @method static self announcement()
 */
final class AnnouncementTypeEnum extends Enum
{
    protected static function values(): array
    {
        return [
            'news' => 'news',
            'announcement' => 'announcement',
        ];
    }
}
