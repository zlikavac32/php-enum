<?php

declare(strict_types=1);

namespace Zlikavac32\Enum\Tests\Fixtures;

/**
 * @method static EnumThatExtendsNonAbstractEnumWithoutEnumerate ENUM_A
 */
abstract class EnumThatExtendsNonAbstractEnumWithoutEnumerate extends NonAbstractEnumWithoutEnumerate
{

    protected static function enumerate(): array
    {
        return [
            'ENUM_A',
        ];
    }
}
