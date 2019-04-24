<?php

declare(strict_types=1);

namespace Zlikavac32\Enum\Examples;

use Zlikavac32\Enum\Enum;

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * You can reference different enum objects of the same type within your enum definition
 */

/**
 * @method static WorldSide NORTH
 * @method static WorldSide SOUTH
 * @method static WorldSide EAST
 * @method static WorldSide WEST
 */
abstract class WorldSide extends Enum
{

    protected static function enumerate(): array
    {
        return [
            'NORTH' => new class extends WorldSide
            {
                /**
                 * @return WorldSide
                 */
                public function opposite(): WorldSide
                {
                    return WorldSide::SOUTH();
                }
            },
            'SOUTH' => new class extends WorldSide
            {
                /**
                 * @return WorldSide
                 */
                public function opposite(): WorldSide
                {
                    return WorldSide::NORTH();
                }
            },
            'EAST' => new class extends WorldSide
            {
                /**
                 * @return WorldSide
                 */
                public function opposite(): WorldSide
                {
                    return WorldSide::WEST();
                }
            },
            'WEST' => new class extends WorldSide
            {
                /**
                 * @return WorldSide
                 */
                public function opposite(): WorldSide
                {
                    return WorldSide::EAST();
                }
            }
        ];
    }

    /**
     * @return WorldSide
     */
    abstract public function opposite(): WorldSide;
}

foreach (WorldSide::iterator() as $worldSide) {
    var_dump(sprintf('Opposite of %s is %s', (string) $worldSide, (string) $worldSide->opposite()));
}

// string(26) "Opposite of NORTH is SOUTH"
// string(26) "Opposite of SOUTH is NORTH"
// string(24) "Opposite of EAST is WEST"
// string(24) "Opposite of WEST is EAST"
