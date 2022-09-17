<?php

namespace App\Enums;

use JetBrains\PhpStorm\ArrayShape;
use Spatie\Enum\Laravel\Enum;

/**
 * Class Application Type Enum
 * @package App\Enums
 *
 * @method static self individual()
 * @method static self team()
 */
final class ApplicationTypeEnum extends Enum
{
    /**
     * @return int[]
     */
    #[ArrayShape([
        'individual' => 'string',
        'team' => 'string',
    ])] protected static function values(): array
    {
        return [
            'individual' => 'individual',
            'team' => 'team',
        ];
    }

    /**
     * @return array
     */
    #[ArrayShape([
        'individual' => 'string',
        'team' => 'string',
    ])] protected static function labels(): array
    {
        return [
            'individual' => __('enum.event.type.individual'),
            'team' => __('enum.event.type.team'),
        ];
    }
}
