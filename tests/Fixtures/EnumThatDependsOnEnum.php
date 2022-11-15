<?php

declare(strict_types=1);

namespace Zlikavac32\ZEnum\Tests\Fixtures;

use Zlikavac32\ZEnum\ZEnum;

/**
 * @method static EnumThatDependsOnEnum ENUM_A
 */
abstract class EnumThatDependsOnEnum extends ZEnum
{
    /**
     * @var ValidStringEnum
     */
    private $validStringEnum;

    public function __construct(ValidStringEnum $validStringEnum)
    {
        parent::__construct();
        $this->validStringEnum = $validStringEnum;
    }

    protected static function enumerate(): array
    {
        return [
            'ENUM_A' => new class(ValidStringEnum::ENUM_A()) extends EnumThatDependsOnEnum
            {
            },
        ];
    }

    public function enum(): ValidStringEnum
    {
        return $this->validStringEnum;
    }
}
