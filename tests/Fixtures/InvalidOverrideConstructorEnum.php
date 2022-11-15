<?php

declare(strict_types=1);

namespace Zlikavac32\ZEnum\Tests\Fixtures;

use Zlikavac32\ZEnum\ZEnum;

/**
 * @method static InvalidOverrideConstructorEnum ENUM_A
 */
class InvalidOverrideConstructorEnum extends ZEnum
{
    public function __construct() { }

    protected static function enumerate(): array
    {
        return [
            'ENUM_A' => new class extends InvalidOverrideConstructorEnum
            {
            }
        ];
    }
}