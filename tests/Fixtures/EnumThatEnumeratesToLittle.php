<?php

declare(strict_types=1);

namespace Zlikavac32\Enum\Tests\Fixtures;

use Zlikavac32\Enum\Enum;

/**
 * @method static EnumThatEnumeratesToLittle ENUM_A
 * @method static EnumThatEnumeratesToLittle ENUM_B
 * @method static EnumThatEnumeratesToLittle ENUM_C
 */
abstract class EnumThatEnumeratesToLittle extends Enum
{
    protected static function enumerate(): array
    {
        return [
            'ENUM_A' => new class extends EnumThatEnumeratesToLittle {}
        ];
    }
}
