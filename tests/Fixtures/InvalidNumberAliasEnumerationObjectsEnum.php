<?php

declare(strict_types=1);

namespace Zlikavac32\Enum\Tests\Fixtures;

use Zlikavac32\Enum\Enum;

abstract class InvalidNumberAliasEnumerationObjectsEnum extends Enum
{
    protected static function enumerate(): array
    {
        return [
            0 => new InvalidNumberAliasEnumerationObjectsDummyEnum()
        ];
    }
}

class InvalidNumberAliasEnumerationObjectsDummyEnum extends InvalidNumberAliasEnumerationObjectsEnum {}
