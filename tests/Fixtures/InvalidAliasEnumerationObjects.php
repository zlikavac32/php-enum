<?php

declare(strict_types=1);

namespace Zlikavac32\Enum\Tests\Fixtures;

use Zlikavac32\Enum\Enum;

abstract class InvalidAliasEnumerationObjects extends Enum
{
    protected static function createEnumerationObjects(): array
    {
        return [
            0 => new class extends InvalidAliasEnumerationObjects {}
        ];
    }
}
