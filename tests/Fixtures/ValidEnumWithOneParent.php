<?php

declare(strict_types=1);

namespace Zlikavac32\Enum\Tests\Fixtures;

/**
 * @method static ValidStringEnum ENUM_A
 */
abstract class ValidEnumWithOneParent extends AbstractEnumWithoutEnumerate
{

    protected static function enumerate(): array
    {
        return [
            'ENUM_A',
        ];
    }
}
