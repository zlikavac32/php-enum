<?php

declare(strict_types=1);

namespace Zlikavac32\ZEnum\Tests\Fixtures;

use Zlikavac32\ZEnum\ZEnum;

/**
 * @method static ValidObjectsEnum ENUM_A
 * @method static ValidObjectsEnum ENUM_B
 */
abstract class ValidObjectsEnum extends ZEnum
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
