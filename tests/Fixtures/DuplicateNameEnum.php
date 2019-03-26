<?php

declare(strict_types=1);

namespace Zlikavac32\Enum\Tests\Fixtures;

use Zlikavac32\Enum\Enum;

/**
 * @method static DuplicateNameEnum ENUM_A
 * @method static DuplicateNameEnum ENUM_A
 */
abstract class DuplicateNameEnum extends Enum
{

}
