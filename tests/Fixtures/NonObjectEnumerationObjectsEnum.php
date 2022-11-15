<?php

declare(strict_types=1);

namespace Zlikavac32\ZEnum\Tests\Fixtures;

use Zlikavac32\ZEnum\ZEnum;

/**
 * @method static NonObjectEnumerationObjectsEnum ALIAS
 */
abstract class NonObjectEnumerationObjectsEnum extends ZEnum
{
    protected static function enumerate(): array
    {
        return [
            'ALIAS' => 1
        ];
    }
}
