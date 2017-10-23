<?php

declare(strict_types=1);

namespace Zlikavac32\Enum\Tests\Fixtures;

use Zlikavac32\Enum\Enum;

/**
 * @method static ValidEnum ENUM_A
 * @method static ValidEnum ENUM_B
 */
abstract class ValidEnum extends Enum
{
    protected static function createEnumerationObjects(): array
    {
        return [
            'ENUM_A' => new class extends ValidEnum
            {
            },
            'ENUM_B'  => new class extends ValidEnum
            {
            },
        ];
    }
}
