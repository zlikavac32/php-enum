<?php

declare(strict_types=1);

namespace Zlikavac32\ZEnum\Examples;

use Zlikavac32\ZEnum\ZEnum;

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * More advanced example where you define common constructor and behaviour for every enum. Then enum objects are
 * created with different arguments
 */

/**
 * @method static Gender MALE
 * @method static Gender FEMALE
 */
abstract class Gender extends ZEnum
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
); // string(1) "F"

var_dump(Gender::MALE() === Gender::FEMALE()); // bool(false)
var_dump(Gender::MALE() === Gender::MALE()); // bool(true)

dumpGender(Gender::MALE()); // string(1) "M"
