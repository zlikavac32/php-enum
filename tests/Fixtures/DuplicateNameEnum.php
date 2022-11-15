<?php

declare(strict_types=1);

namespace Zlikavac32\ZEnum\Tests\Fixtures;

use Zlikavac32\ZEnum\ZEnum;

/**
 * @method static DuplicateNameEnum ENUM_A
 * @method static DuplicateNameEnum ENUM_A
 */
abstract class DuplicateNameEnum extends ZEnum
{

}
