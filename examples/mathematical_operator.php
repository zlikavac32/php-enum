<?php

declare(strict_types=1);

namespace Zlikavac32\Enum\Examples;

use LogicException;
use Zlikavac32\Enum\Enum;

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Main benefit of this enum concept is chance to use polymorphism. It's possible to define enum class with abstract
 * methods or with a constructor. Let's explore this example with some mathematical operators that should have
 * polymorphic behaviour.
 *
 * We will define four enums and we will provide operations for every enum that exists. To achieve that in our named
 * enum class we define abstract public function apply(float $left, float $right): float; method and then implement in
 * each anonymous class the required behaviour
 */

/**
 * @method static MathematicalOperator PLUS
 * @method static MathematicalOperator MINUS
 * @method static MathematicalOperator TIMES
 * @method static MathematicalOperator DIV
 */
abstract class MathematicalOperator extends Enum
{
    protected static function enumerate(): array
    {
        return [
            'PLUS'  => new class extends MathematicalOperator
            {
                public function apply(float $left, float $right): float
                {
                    return $left + $right;
                }

                public function __toString(): string
                {
                    return '+';
                }
            },
            'MINUS' => new class extends MathematicalOperator
            {
                public function apply(float $left, float $right): float
                {
                    return $left - $right;
                }

                public function __toString(): string
                {
                    return '-';
                }
            },
            'TIMES' => new class extends MathematicalOperator
            {
                public function apply(float $left, float $right): float
                {
                    return $left * $right;
                }

                public function __toString(): string
                {
                    return '*';
                }
            },
            'DIV'   => new class extends MathematicalOperator
            {
                public function apply(float $left, float $right): float
                {
                    if (.0 === $right) {
                        throw new LogicException('Division by zero');
                    }

                    return $left / $right;
                }

                public function __toString(): string
                {
                    return '/';
                }
            },
        ];
    }

    abstract public function apply(float $left, float $right): float;
}

//Let PLUS enum apply its operation
var_dump(
    MathematicalOperator::PLUS()
        ->apply(10, 20)
); // double(30)

//We can iterate over every operator and let him apply its operation. We also override __toString() method

foreach (MathematicalOperator::iterator() as $operator) {
    $left = 10;
    $right = 20;
    var_dump(
        sprintf(
            '%.2lf %s %.2lf = %.2lf',
            $left,
            (string) $operator,
            $right,
            $operator->apply(
                $left,
                $right
            )
        )
    );
}

// string(21) "10.00 + 20.00 = 30.00"
// string(22) "10.00 - 20.00 = -10.00"
// string(22) "10.00 * 20.00 = 200.00"
// string(20) "10.00 / 20.00 = 0.50"
