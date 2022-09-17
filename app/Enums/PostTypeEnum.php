<?php

namespace App\Enums;
use Spatie\Enum\Laravel\Enum;

/**
 * Class PostTypeEnum
 * @package App\Enums
 *
 * @method static self user()
 * @method static self team()
 */
final class PostTypeEnum extends Enum
{
    protected static function values(): array
    {
        return [
            'user' => 'user',
            'team' => 'team',
        ];
    }
}
