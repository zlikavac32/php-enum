<?php

declare(strict_types=1);

namespace Zlikavac32\Enum\Tests\Fixtures;

/**
 * @method static EnumThatExtendsValidEnum ENUM_A
 */
abstract class EnumThatExtendsValidEnum extends ValidStringEnum
{
    protected static function enumerate(): array
    {
        return [
            'ENUM_A'
        ];
    }
}
