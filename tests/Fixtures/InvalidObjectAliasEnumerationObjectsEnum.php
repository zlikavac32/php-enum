<?php

declare(strict_types=1);

namespace Zlikavac32\ZEnum\Tests\Fixtures;

use Zlikavac32\ZEnum\ZEnum;

/**
 * @method static InvalidObjectAliasEnumerationObjectsEnum ENUM
 */
abstract class InvalidObjectAliasEnumerationObjectsEnum extends ZEnum
{
    protected static function enumerate(): array
    {
        return [
            'ENUM' => new class extends InvalidObjectAliasEnumerationObjectsEnum {},
            0 => new class extends InvalidObjectAliasEnumerationObjectsEnum {}
        ];
    }
}
