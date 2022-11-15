<?php

declare(strict_types=1);

namespace Zlikavac32\ZEnum\Tests\Fixtures;

use Zlikavac32\ZEnum\ZEnum;

/**
 * @method static OrdinalWithinEnumerateEnum ENUM_A
 */
abstract class OrdinalWithinEnumerateEnum extends ZEnum
{
    protected static function enumerate(): array
    {
        $obj = new class extends OrdinalWithinEnumerateEnum
        {
        };

        $obj->ordinal();

        return [
            'ENUM_A' => $obj
        ];
    }
}
