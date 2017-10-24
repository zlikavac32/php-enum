<?php

declare(strict_types=1);

namespace Zlikavac32\Enum\Tests\Fixtures;

use Zlikavac32\Enum\Enum;

/**
 * @method static NameWithinEnumerateEnum ENUM_A
 */
abstract class NameWithinEnumerateEnum extends Enum
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
