<?php

declare(strict_types=1);

namespace Zlikavac32\Enum\Tests\Fixtures;

use Zlikavac32\Enum\Enum;

abstract class WrongClassEnumerationObjects extends Enum
{
    protected static function createEnumerationObjects(): array
    {
        return [
            'ALIAS' => Dummy::A()
        ];
    }
}

/**
 * @method static Dummy A
 */
abstract class Dummy extends Enum
{
    protected static function createEnumerationObjects(): array
    {
        return [
            'A' => new ADummy()
        ];
    }
}

//This exists only to get some portable class name in exception message
class ADummy extends Dummy {}
