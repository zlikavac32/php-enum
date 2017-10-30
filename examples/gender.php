<?php

declare(strict_types=1);

namespace Zlikavac32\Rick\Examples;

use Zlikavac32\Enum\Enum;

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * More advanced example where you define common constructor and behaviour for every enum. Then enum objects are
 * created with different arguments
 */

/**
 * @method static Gender MALE
 * @method static Gender FEMALE
 */
abstract class Gender extends Enum
{
    /**
     * @var string
     */
    private $symbol;

    public function __construct(string $symbol)
    {
        parent::__construct();
        $this->symbol = $symbol;
    }

    protected static function enumerate(): array
    {
        //Create enums with different constructor arguments
        return [
            'MALE'   => new class('M') extends Gender
            {
            },
            'FEMALE' => new class('F') extends Gender
            {
            },
        ];
    }

    public function symbol(): string
    {
        return $this->symbol;
    }
}

function dumpGender(Gender $gender)
{
    var_dump($gender->symbol());
}

var_dump(
    Gender::FEMALE()
        ->symbol()
);
var_dump(Gender::MALE() === Gender::FEMALE());
var_dump(Gender::MALE() === Gender::MALE());

dumpGender(Gender::MALE());
