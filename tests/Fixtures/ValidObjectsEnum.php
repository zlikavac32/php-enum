<?php

declare(strict_types=1);

namespace Zlikavac32\Enum\Tests\Fixtures;

use Zlikavac32\Enum\Enum;

/**
 * @method static ValidObjectsEnum ENUM_A
 * @method static ValidObjectsEnum ENUM_B
 */
abstract class ValidObjectsEnum extends Enum
{
    protected static function enumerate(): array
    {
        return [
            'ENUM_A' => new class extends ValidObjectsEnum
            {
            },
            'ENUM_B'  => new class extends ValidObjectsEnum
            {
            },
        ];
    }
}
