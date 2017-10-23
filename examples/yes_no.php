<?php

declare(strict_types=1);

namespace Zlikavac32\Rick\Examples;

use Zlikavac32\Enum\Enum;

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * @method static YesNo YES
 * @method static YesNo NO
 */
abstract class YesNo extends Enum
{
    protected static function createEnumerationObjects(): array
    {
        return [
            'YES' => new class extends YesNo
            {
            },
            'NO'  => new class extends YesNo
            {
            },
        ];
    }
}

function yesNo(YesNo $yesNo)
{
    switch ($yesNo) {
        case YesNo::NO():
            var_dump('No');
            break;
        case YesNo::YES():
            var_dump('Yes');
            break;
    }
}

yesNo(YesNo::NO());
yesNo(YesNo::YES());

var_dump(
    YesNo::NO()
        ->ordinal(),
    YesNo::YES()
        ->ordinal()
);
