<?php

declare(strict_types=1);

namespace Zlikavac32\Rick\Examples;

use Zlikavac32\Enum\Enum;

require_once __DIR__ . '/../vendor/autoload.php';

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

                public function __toString()
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

                public function __toString()
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

                public function __toString()
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

                public function __toString()
                {
                    return '/';
                }
            },
        ];
    }

    abstract public function apply(float $left, float $right): float;
}

var_dump(
    MathematicalOperator::PLUS()
        ->apply(10, 20)
);

/* @var MathematicalOperator $operator */
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
