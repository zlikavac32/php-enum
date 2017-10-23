<?php

declare(strict_types=1);

namespace Zlikavac32\Enum\Tests\Fixtures;

use Zlikavac32\Enum\Enum;

abstract class ZeroLengthEnumerationObjects extends Enum
{
    protected static function createEnumerationObjects(): array
    {
        return [];
    }
}
