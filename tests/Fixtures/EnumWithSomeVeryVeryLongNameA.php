<?php

declare(strict_types=1);

namespace Zlikavac32\Enum\Tests\Fixtures;

use Zlikavac32\Enum\Enum;

/**
 * @method static EnumWithSomeVeryVeryLongNameA ENUM_A
 * @method static EnumWithSomeVeryVeryLongNameA ENUM_B
 */
abstract class EnumWithSomeVeryVeryLongNameA extends Enum
{
    protected static function enumerate(): array
    {
        return [
            'ENUM_A',
            'ENUM_B',
        ];
    }
}
