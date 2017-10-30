<?php

declare(strict_types=1);

namespace Zlikavac32\Enum\Tests\Fixtures;

use Zlikavac32\Enum\Enum;

/**
 * @method static DuplicateNameEnum ENUM_A
 * @method static DuplicateNameEnum ENUM_B
 */
abstract class DuplicateNameEnum extends Enum
{
    protected static function enumerate(): array
    {
        return [
            'ENUM_A',
            'ENUM_A',
        ];
    }
}
