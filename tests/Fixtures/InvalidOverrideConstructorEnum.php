<?php

declare(strict_types=1);

namespace Zlikavac32\Enum\Tests\Fixtures;

use Zlikavac32\Enum\Enum;

/**
 * @method static InvalidOverrideConstructorEnum ENUM_A
 */
class InvalidOverrideConstructorEnum extends Enum
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