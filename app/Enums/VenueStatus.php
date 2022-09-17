<?php

namespace App\Enums;

use JetBrains\PhpStorm\ArrayShape;
use Spatie\Enum\Laravel\Enum;

/**
 * Class VenueStatus
 * @package App\Enums
 *
 * @method static self active()
 * @method static self inactive()
 */
final class VenueStatus extends Enum
{
    /**
     * @return int[]
     */
    #[ArrayShape([
        'active' => 'integer',
        'inactive' => 'integer',
    ])] protected static function values(): array
    {
        return [
            'active' => 1,
            'inactive' => 0,
        ];
    }

    /**
     * @return array
     */
    #[ArrayShape([
        'active' => 'integer',
        'inactive' => 'integer',
    ])] protected static function labels(): array
    {
        return [
            'active' => __('enum.active'),
            'inactive' => __('enum.inactive'),
        ];
    }
}
