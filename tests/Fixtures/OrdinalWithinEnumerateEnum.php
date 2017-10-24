<?php

declare(strict_types=1);

namespace Zlikavac32\Enum\Tests\Fixtures;

use Zlikavac32\Enum\Enum;

/**
 * @method static OrdinalWithinEnumerateEnum ENUM_A
 */
abstract class OrdinalWithinEnumerateEnum extends Enum
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
