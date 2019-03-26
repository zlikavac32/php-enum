<?php

declare(strict_types=1);

namespace Zlikavac32\Enum\Tests\Fixtures;

use Zlikavac32\Enum\Enum;

/**
 * @method static EnumThatEnumeratesToMuch ENUM_A
 */
abstract class EnumThatEnumeratesToMuch extends Enum
{
    protected static function enumerate(): array
    {
        return [
            'ENUM_A' => new class extends EnumThatEnumeratesToMuch {},
            'ENUM_B' => new class extends EnumThatEnumeratesToMuch {},
            'ENUM_C' => new class extends EnumThatEnumeratesToMuch {}
        ];
    }
}
