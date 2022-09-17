<?php

namespace App\Enums;
use Spatie\Enum\Laravel\Enum;

/**
 * Class PostTypeEnum
 * @package App\Enums
 *
 * @method static self pickup()
 * @method static self sport()
 * @method static self league()
 * @method static self session()
 */
final class EventTypeEnum extends Enum
{
    protected static function values(): array
    {
        return [
            'pickup' => 'pickup',
            'sport' => 'sport',
            'league' => 'league',
            'session' => 'session',
        ];
    }
}
