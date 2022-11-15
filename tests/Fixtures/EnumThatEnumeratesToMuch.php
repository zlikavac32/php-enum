<?php

declare(strict_types=1);

namespace Zlikavac32\ZEnum\Tests\Fixtures;

use Zlikavac32\ZEnum\ZEnum;

/**
 * @method static EnumThatEnumeratesToMuch ENUM_A
 */
abstract class EnumThatEnumeratesToMuch extends ZEnum
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
