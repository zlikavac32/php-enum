<?php

declare(strict_types=1);

namespace Zlikavac32\Enum\Tests\Fixtures;

use Zlikavac32\Enum\Enum;

/**
 * @method static WrongClassEnumerationObjectsEnum ALIAS
 */
abstract class WrongClassEnumerationObjectsEnum extends Enum
{
    protected static function enumerate(): array
    {
        return [
            'ALIAS' => WrongClassEnumerationObjectsDummyEnum::A()
        ];
    }
}

/**
 * @method static WrongClassEnumerationObjectsDummyEnum A
 */
abstract class WrongClassEnumerationObjectsDummyEnum extends Enum
{
    protected static function enumerate(): array
    {
        return [
            'A' => new AWrongClassEnumerationObjectsDummyEnum()
        ];
    }
}

//This exists only to get some portable class name in exception message
class AWrongClassEnumerationObjectsDummyEnum extends WrongClassEnumerationObjectsDummyEnum {}
