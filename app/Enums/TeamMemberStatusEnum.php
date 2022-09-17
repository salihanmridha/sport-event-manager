<?php

namespace App\Enums;
use Spatie\Enum\Laravel\Enum;

/**
 * Class PostTypeEnum
 * @package App\Enums
 *
 * @method static self active()
 * @method static self inactive()
 */
final class TeamMemberStatusEnum extends Enum
{
    protected static function values(): array
    {
        return [
            'active' => 'active',
            'inactive' => 'inactive',
        ];
    }

    public static function labels(): array
    {
        return [
            'active' => 'Active',
            'inactive' => 'Inactive',
        ];
    }
}
