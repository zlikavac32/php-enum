<?php

declare(strict_types=1);

namespace Zlikavac32\Enum\Tests\Fixtures;

use Zlikavac32\Enum\Enum;

/**
 * @method static InvalidAliasNameEnum INVA LID
 */
abstract class InvalidAliasNameEnum extends Enum
{
    protected static function enumerate(): array
    {
        return [
            'INVA LID',
        ];
    }
}
