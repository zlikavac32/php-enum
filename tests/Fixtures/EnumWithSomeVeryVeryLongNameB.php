<?php

declare(strict_types=1);

namespace Zlikavac32\Enum\Tests\Fixtures;

use Zlikavac32\Enum\Enum;

/**
 * @method static EnumWithSomeVeryVeryLongNameB ENUM_A
 * @method static EnumWithSomeVeryVeryLongNameB ENUM_B
 */
abstract class EnumWithSomeVeryVeryLongNameB extends Enum
{
    protected static function enumerate(): array
    {
        return [
            'ENUM_A',
            'ENUM_B',
        ];
    }
}
