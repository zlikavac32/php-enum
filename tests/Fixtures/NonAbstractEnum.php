<?php

declare(strict_types=1);

namespace Zlikavac32\Enum\Tests\Fixtures;

use Zlikavac32\Enum\Enum;

/**
 * @method static NonAbstractEnum ENUM_A
 */
class NonAbstractEnum extends Enum
{
    protected static function enumerate(): array
    {
        return [
            'ENUM_A'
        ];
    }
}
