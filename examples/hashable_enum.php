<?php

declare(strict_types=1);

namespace Zlikavac32\Rick\Examples;

use Ds\Hashable;
use Ds\Set;
use Zlikavac32\Enum\Enum;

require_once __DIR__ . '/../vendor/autoload.php';

if (
    !interface_exists(Hashable::class)
    ||
    !class_exists(Set::class)
) {
    fwrite(STDERR, "You need the ds library to run this example (composer or native version both should work well)");

    exit(1);
}

/**
 * Example that show how to extend Enum with custom behaviour
 */
abstract class HashableEnum extends Enum implements Hashable
{

    public function equals($object): bool
    {
        return $object === $this;
    }

    public function hash(): string
    {
        // this works under assumption that you use this library correctly (same object has same hash)
        return spl_object_hash($this);
    }
}

/**
 * @method static YesNo YES
 * @method static YesNo NO
 */
abstract class YesNo extends HashableEnum
{

    protected static function enumerate(): array
    {
        return [
            'YES',
            'NO',
        ];
    }
}

var_dump(YesNo::YES() instanceof Hashable); // bool(true)

$set = new Set();

$set->add(YesNo::NO());

var_dump($set->contains(YesNo::NO())); // bool(true)
var_dump($set->contains(YesNo::YES())); // bool(false)

$set->add(YesNo::YES());

var_dump($set->contains(YesNo::NO())); // bool(true)
var_dump($set->contains(YesNo::YES())); // bool(true)

$set->remove(YesNo::NO());

var_dump($set->contains(YesNo::NO())); // bool(false)
var_dump($set->contains(YesNo::YES())); // bool(true)
