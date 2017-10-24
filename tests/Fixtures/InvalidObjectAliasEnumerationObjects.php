<?php

declare(strict_types=1);

namespace Zlikavac32\Enum\Tests\Fixtures;

use Zlikavac32\Enum\Enum;

abstract class InvalidObjectAliasEnumerationObjects extends Enum
{
    protected static function enumerate(): array
    {
        return [
            'ENUM' => new class extends InvalidObjectAliasEnumerationObjects {},
            0 => new class extends InvalidObjectAliasEnumerationObjects {}
        ];
    }
}
