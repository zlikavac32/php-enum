<?php

declare(strict_types=1);

namespace Zlikavac32\ZEnum\Tests\Fixtures;

use Zlikavac32\ZEnum\ZEnum;

/**
 * @method static EnumThatEnumeratesToLittle ENUM_A
 * @method static EnumThatEnumeratesToLittle ENUM_B
 * @method static EnumThatEnumeratesToLittle ENUM_C
 */
abstract class EnumThatEnumeratesToLittle extends ZEnum
{
    protected static function enumerate(): array
    {
        return [
            'ENUM_A' => new class extends EnumThatEnumeratesToLittle {}
        ];
    }
}
