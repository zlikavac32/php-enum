<?php

declare(strict_types=1);

namespace Zlikavac32\Enum\Tests;

use LogicException;
use PHPUnit\Framework\TestCase;
use Zlikavac32\Enum\Tests\Fixtures\DefaultCreateEnumerationObjects;
use Zlikavac32\Enum\Tests\Fixtures\InvalidAliasEnumerationObjects;
use Zlikavac32\Enum\Tests\Fixtures\NonObjectEnumerationObjects;
use Zlikavac32\Enum\Tests\Fixtures\ValidEnum;
use Zlikavac32\Enum\Tests\Fixtures\WrongClassEnumerationObjects;
use Zlikavac32\Enum\Tests\Fixtures\ZeroLengthEnumerationObjects;

class EnumTest extends TestCase
{

    public function testThatEnumObjectsHaveValidOrdinal(): void
    {
        $this->assertSame(
            0,
            ValidEnum::ENUM_A()
                ->ordinal()
        );
        $this->assertSame(
            1,
            ValidEnum::ENUM_B()
                ->ordinal()
        );
    }

    public function testThatEnumObjectsHaveValidName(): void
    {
        $this->assertSame(
            'ENUM_A',
            ValidEnum::ENUM_A()
                ->name()
        );
        $this->assertSame(
            'ENUM_B',
            ValidEnum::ENUM_B()
                ->name()
        );
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage Cloning enum object is not allowed
     */
    public function testThatCloneIsNotSupported(): void
    {
        clone ValidEnum::ENUM_A();
    }

    public function testThatEnumObjectsHaveValidDefaultToStringImplementation(): void
    {
        $this->assertSame('ENUM_A', (string) ValidEnum::ENUM_A());
        $this->assertSame('ENUM_B', (string) ValidEnum::ENUM_B());
    }

    public function testThatIteratorIteratesOverEnumObjects(): void
    {
        $this->assertSame(
            [
                ValidEnum::ENUM_A(),
                ValidEnum::ENUM_B(),
            ],
            iterator_to_array(ValidEnum::iterator())
        );
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage No argument must be provided when calling
     *     Zlikavac32\Enum\Tests\Fixtures\ValidEnum::ENUM_B
     */
    public function testThatEnumObjectCallsMustBeWithoutArguments(): void
    {
        ValidEnum::ENUM_B(0);
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage Enumeration objects array in class
     *     Zlikavac32\Enum\Tests\Fixtures\ZeroLengthEnumerationObjects can not be empty
     */
    public function testThatZeroLengthEnumerationObjectConfigurationThrowsException(): void
    {
        ZeroLengthEnumerationObjects::iterator();
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage You must provide protected static function createEnumerationObjects(): array method in
     *     your enum class Zlikavac32\Enum\Tests\Fixtures\DefaultCreateEnumerationObjects
     */
    public function testThatDefaultEnumerationObjectConfigurationThrowsException(): void
    {
        DefaultCreateEnumerationObjects::iterator();
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage Enum object I_DONT_EXIST missing in Zlikavac32\Enum\Tests\Fixtures\ValidEnum
     */
    public function testThatAccessingNonExistingEnumThrowsException(): void
    {
        ValidEnum::I_DONT_EXIST();
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage Alias 0 in enum class Zlikavac32\Enum\Tests\Fixtures\InvalidAliasEnumerationObjects is
     *     not valid alias
     */
    public function testThatInvalidAliasThrowsException(): void
    {
        InvalidAliasEnumerationObjects::iterator();
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage Enum object in class Zlikavac32\Enum\Tests\Fixtures\WrongClassEnumerationObjects must
     *     be an instance of Zlikavac32\Enum\Enum (an instance of Zlikavac32\Enum\Tests\Fixtures\ADummy received)
     */
    public function testThatWrongEnumInstanceThrowsException(): void
    {
        WrongClassEnumerationObjects::iterator();
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage Enum object in class Zlikavac32\Enum\Tests\Fixtures\NonObjectEnumerationObjects must
     *     be an instance of Zlikavac32\Enum\Enum (integer received)
     */
    public function testThatObjectEnumThrowsException(): void
    {
        NonObjectEnumerationObjects::iterator();
    }
}
