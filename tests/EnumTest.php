<?php

declare(strict_types=1);

namespace Zlikavac32\Enum\Tests;

use LogicException;
use PHPUnit\Framework\TestCase;
use Zlikavac32\Enum\Tests\Fixtures\DefaultCreateEnumerationObjects;
use Zlikavac32\Enum\Tests\Fixtures\DuplicateNameEnum;
use Zlikavac32\Enum\Tests\Fixtures\EnumThatDependsOnEnum;
use Zlikavac32\Enum\Tests\Fixtures\InvalidAliasNameEnum;
use Zlikavac32\Enum\Tests\Fixtures\InvalidNumberAliasEnumerationObjects;
use Zlikavac32\Enum\Tests\Fixtures\InvalidObjectAliasEnumerationObjects;
use Zlikavac32\Enum\Tests\Fixtures\InvalidOverrideConstructorEnum;
use Zlikavac32\Enum\Tests\Fixtures\NameWithinEnumerateEnum;
use Zlikavac32\Enum\Tests\Fixtures\NonAbstractEnum;
use Zlikavac32\Enum\Tests\Fixtures\NonObjectEnumerationObjects;
use Zlikavac32\Enum\Tests\Fixtures\OrdinalWithinEnumerateEnum;
use Zlikavac32\Enum\Tests\Fixtures\ValidObjectsEnum;
use Zlikavac32\Enum\Tests\Fixtures\ValidStringEnum;
use Zlikavac32\Enum\Tests\Fixtures\WrongClassEnumerationObjects;
use Zlikavac32\Enum\Tests\Fixtures\ZeroLengthEnumerationObjects;

class EnumTest extends TestCase
{

    public function testThatEnumObjectsHaveValidOrdinal(): void
    {
        $this->assertSame(
            0,
            ValidObjectsEnum::ENUM_A()
                ->ordinal()
        );
        $this->assertSame(
            1,
            ValidObjectsEnum::ENUM_B()
                ->ordinal()
        );
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage You can not retrieve ordinal within enumerate()
     */
    public function testThatOrdinalThrowExceptionUntilValueIsDefined(): void
    {
        OrdinalWithinEnumerateEnum::ENUM_A();
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage You can not retrieve name within enumerate()
     */
    public function testThatNameThrowExceptionUntilValueIsDefined(): void
    {
        NameWithinEnumerateEnum::ENUM_A();
    }

    public function testThatEnumObjectsHaveValidName(): void
    {
        $this->assertSame(
            'ENUM_A',
            ValidObjectsEnum::ENUM_A()
                ->name()
        );
        $this->assertSame(
            'ENUM_B',
            ValidObjectsEnum::ENUM_B()
                ->name()
        );
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage Cloning enum object is not allowed
     */
    public function testThatCloneIsNotSupported(): void
    {
        clone ValidObjectsEnum::ENUM_A();
    }

    public function testThatValueOfReturnsRequestedEnum(): void
    {
        $this->assertSame(ValidObjectsEnum::ENUM_A(), ValidObjectsEnum::valueOf('ENUM_A'));
    }

    /**
     * @expectedException \Zlikavac32\Enum\EnumNotFoundException
     * @expectedExceptionMessage Enum object I_DONT_EXIST missing in Zlikavac32\Enum\Tests\Fixtures\ValidObjectsEnum
     */
    public function testThatValueOfThrowsExceptionWhenEnumDoesNotExist(): void
    {
        ValidObjectsEnum::valueOf('I_DONT_EXIST');
    }

    public function testThatEnumObjectsHaveValidDefaultToStringImplementation(): void
    {
        $this->assertSame('ENUM_A', (string) ValidObjectsEnum::ENUM_A());
        $this->assertSame('ENUM_B', (string) ValidObjectsEnum::ENUM_B());
    }

    public function testThatIteratorIteratesOverEnumObjects(): void
    {
        $this->assertSame(
            [
                ValidObjectsEnum::ENUM_A(),
                ValidObjectsEnum::ENUM_B(),
            ],
            iterator_to_array(ValidObjectsEnum::iterator())
        );
    }

    public function testThatValuesCanBeReturned(): void
    {
        $this->assertSame(
            [
                ValidStringEnum::ENUM_A(),
                ValidStringEnum::ENUM_B(),
            ],
            ValidStringEnum::values()
        );
    }

    public function testThatIteratorIteratesOverStringEnumObjects(): void
    {
        $this->assertSame(
            [
                ValidStringEnum::ENUM_A(),
                ValidStringEnum::ENUM_B(),
            ],
            iterator_to_array(ValidStringEnum::iterator())
        );
    }

    public function testThatDependentEnumCanBeCreated(): void
    {
        $this->assertSame(ValidStringEnum::ENUM_A(), EnumThatDependsOnEnum::ENUM_A()->enum());
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage No argument must be provided when calling
     *     Zlikavac32\Enum\Tests\Fixtures\ValidObjectsEnum::ENUM_B
     */
    public function testThatEnumObjectCallsMustBeWithoutArguments(): void
    {
        ValidObjectsEnum::ENUM_B(0);
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
     * @expectedExceptionMessage Enum class Zlikavac32\Enum\Tests\Fixtures\NonAbstractEnum must be declared as abstract
     */
    public function testThatNonAbstractEnumThrowsException(): void
    {
        NonAbstractEnum::ENUM_A();
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage Alias "INVA LID" does not match pattern /^[a-zA-Z_][a-zA-Z_0-9]*$/i
     */
    public function testThatInvalidAliasNameThrowsException(): void
    {
        InvalidAliasNameEnum::values();
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage You must provide protected static function enumerate(): array method in
     *     your enum class Zlikavac32\Enum\Tests\Fixtures\DefaultCreateEnumerationObjects
     */
    public function testThatDefaultEnumerationObjectConfigurationThrowsException(): void
    {
        DefaultCreateEnumerationObjects::iterator();
    }

    /**
     * @expectedException \Zlikavac32\Enum\EnumNotFoundException
     * @expectedExceptionMessage Enum object I_DONT_EXIST missing in Zlikavac32\Enum\Tests\Fixtures\ValidObjectsEnum
     */
    public function testThatAccessingNonExistingEnumThrowsException(): void
    {
        ValidObjectsEnum::I_DONT_EXIST();
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage Alias 0 in enum class
     *     Zlikavac32\Enum\Tests\Fixtures\InvalidObjectAliasEnumerationObjects is not valid alias
     */
    public function testThatInvalidObjectAliasThrowsException(): void
    {
        InvalidObjectAliasEnumerationObjects::iterator();
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage Alias (object instance of
     *     Zlikavac32\Enum\Tests\Fixtures\InvalidNumberAliasEnumerationObjectsDummy) in enum class
     *     Zlikavac32\Enum\Tests\Fixtures\InvalidNumberAliasEnumerationObjects is not valid alias
     */
    public function testThatInvalidNumberAliasThrowsException(): void
    {
        InvalidNumberAliasEnumerationObjects::iterator();
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

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage Serialization/deserialization of enum object is not allowed
     */
    public function testThatSetStateThrowsException(): void
    {
        ValidStringEnum::ENUM_A()->__set_state();
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage Serialization/deserialization of enum object is not allowed
     */
    public function testThatSleepThrowsException(): void
    {
        ValidStringEnum::ENUM_A()->__sleep();
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage Serialization/deserialization of enum object is not allowed
     */
    public function testThatWakeupThrowsException(): void
    {
        ValidStringEnum::ENUM_A()->__wakeup();
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage Serialization/deserialization of enum object is not allowed
     */
    public function testThatSerializeThrowsException(): void
    {
        ValidStringEnum::ENUM_A()->serialize();
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage Serialization/deserialization of enum object is not allowed
     */
    public function testThatUnserializeThrowsException(): void
    {
        ValidStringEnum::ENUM_A()->unserialize('');
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage It seems that enum is not correctly initialized. Did you forget to call
     *     parent::__construct() in enum Zlikavac32\Enum\Tests\Fixtures\InvalidOverrideConstructorEnum?
     */
    public function testThatConstructMustBeCalled(): void
    {
        (new InvalidOverrideConstructorEnum())->name();
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage It seems you tried to manually create enum outside of enumerate() method for enum Zlikavac32\Enum\Tests\Fixtures\NonAbstractEnum
     */
    public function testThatNameThrowsExceptionWhenNotConstructedCorrectly(): void
    {
        (new NonAbstractEnum())->name();
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage It seems you tried to manually create enum outside of enumerate() method for enum Zlikavac32\Enum\Tests\Fixtures\NonAbstractEnum
     */
    public function testThatOrdinalThrowsExceptionWhenNotConstructedCorrectly(): void
    {
        (new NonAbstractEnum())->ordinal();
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage It seems you tried to manually create enum outside of enumerate() method for enum Zlikavac32\Enum\Tests\Fixtures\NonAbstractEnum
     */
    public function testThatToStringThrowsExceptionWhenNotConstructedCorrectly(): void
    {
        (new NonAbstractEnum())->__toString();
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage Duplicate name exists in enum Zlikavac32\Enum\Tests\Fixtures\DuplicateNameEnum
     */
    public function testThatDuplicateElementThrowsException(): void
    {
        DuplicateNameEnum::ENUM_A();
    }
}
