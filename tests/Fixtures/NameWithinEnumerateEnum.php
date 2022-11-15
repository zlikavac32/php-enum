<?php

declare(strict_types=1);

namespace Zlikavac32\ZEnum\Tests\Fixtures;

use Zlikavac32\ZEnum\ZEnum;

/**
 * @method static NameWithinEnumerateEnum ENUM_A
 */
abstract class NameWithinEnumerateEnum extends ZEnum
{
    protected static function enumerate(): array
    {
        $obj = new class extends NameWithinEnumerateEnum
        {
        };

        $obj->name();

        return [
            'ENUM_A' => $obj
        ];
    }
}
