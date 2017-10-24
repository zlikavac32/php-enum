<?php

declare(strict_types=1);

namespace Zlikavac32\Enum\Tests\Fixtures;

use Zlikavac32\Enum\Enum;

abstract class InvalidNumberAliasEnumerationObjects extends Enum
{
    protected static function enumerate(): array
    {
        return [
            0 => new InvalidNumberAliasEnumerationObjectsDummy()
        ];
    }
}

class InvalidNumberAliasEnumerationObjectsDummy extends InvalidNumberAliasEnumerationObjects {}
