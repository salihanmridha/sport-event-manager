<?php

namespace App\Enums;

use JetBrains\PhpStorm\ArrayShape;
use Spatie\Enum\Laravel\Enum;

/**
 * Class GenderEnum
 * @package App\Enums
 *
 * @method static self male()
 * @method static self female()
 * @method static self lgbt()
 * @method static self all()
 */
final class GenderEnum extends Enum
{
    /**
     * @return int[]
     */
    #[ArrayShape([
        'male' => 'string',
        'female' => 'string',
        'all' => 'string',
        'lgbt' => 'string',
    ])] protected static function values(): array
    {
        return [
            'male' => 'male',
            'female' => 'female',
            'all' => 'all',
            'lgbt' => 'lgbt+',
        ];
    }

    /**
     * @return array
     */
    #[ArrayShape([
        'male' => 'string',
        'female' => 'string',
        'lgbt' => 'string',
        'all' => 'string'
    ])] protected static function labels(): array
    {
        return [
            'male' => __('enum.gender.male'),
            'female' => __('enum.gender.female'),
            'lgbt' => __('enum.gender.lgbt'),
            'all' => __('enum.gender.all'),
        ];
    }
}
