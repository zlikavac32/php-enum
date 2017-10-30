<?php

declare(strict_types=1);

namespace Zlikavac32\Enum\Tests\Fixtures;

use Zlikavac32\Enum\Enum;

abstract class ZeroLengthEnumerationObjectsEnum extends Enum
{
    protected static function enumerate(): array
    {
        return [];
    }
}
