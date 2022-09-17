<?php

namespace App\Enums;

use JetBrains\PhpStorm\ArrayShape;
use Spatie\Enum\Laravel\Enum;

/**
 * Class Event Enum
 * @package App\Enums
 *
 * @method static self on_going()
 * @method static self completed()
 * @method static self canceled()
 */
final class EventStatusEnum extends Enum
{
    /**
     * @return int[]
     */
    #[ArrayShape([
        'on_going' => 'string',
        'completed' => 'string',
        'canceled' => 'string',
    ])] protected static function values(): array
    {
        return [
            'on_going' => 'on_going',
            'completed' => 'completed',
            'canceled' => 'canceled'
        ];
    }

    /**
     * @return array
     */
    #[ArrayShape([
        'on_going' => 'string',
        'completed' => 'string',
        'canceled' => 'string',
    ])] protected static function labels(): array
    {
        return [
            'on_going' => __('enum.event.on-going'),
            'completed' => __('enum.event.completed'),
            'canceled' => __('enum.event.canceled'),
        ];
    }
}
