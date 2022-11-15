<?php

declare(strict_types=1);

namespace Zlikavac32\ZEnum\Tests\Fixtures;

/**
 * @method static EnumThatExtendsValidObjectsEnum ENUM_A
 */
abstract class EnumThatExtendsValidObjectsEnum extends ValidObjectsEnum
{
    protected static function enumerate(): array
    {
        return [
            'ENUM_A' => new class extends EnumThatExtendsValidObjectsEnum {}
        ];
    }
}
