<?php

declare(strict_types=1);

namespace Zlikavac32\Enum\Tests\Fixtures;

use Zlikavac32\Enum\Enum;

/**
 * @method static InvalidObjectAliasEnumerationObjectsEnum ENUM
 */
abstract class InvalidObjectAliasEnumerationObjectsEnum extends Enum
{
    protected static function enumerate(): array
    {
        return [
            'ENUM' => new class extends InvalidObjectAliasEnumerationObjectsEnum {},
            0 => new class extends InvalidObjectAliasEnumerationObjectsEnum {}
        ];
    }
}
