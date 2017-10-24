<?php

declare(strict_types=1);

namespace Zlikavac32\Enum\Tests\Fixtures;

use Zlikavac32\Enum\Enum;

abstract class WrongClassEnumerationObjects extends Enum
{
    protected static function enumerate(): array
    {
        return [
            'ALIAS' => WrongClassEnumerationObjectsDummy::A()
        ];
    }
}

/**
 * @method static WrongClassEnumerationObjectsDummy A
 */
abstract class WrongClassEnumerationObjectsDummy extends Enum
{
    protected static function enumerate(): array
    {
        return [
            'A' => new AWrongClassEnumerationObjectsDummy()
        ];
    }
}

//This exists only to get some portable class name in exception message
class AWrongClassEnumerationObjectsDummy extends WrongClassEnumerationObjectsDummy {}
