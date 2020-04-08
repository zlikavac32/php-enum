<?php

declare(strict_types=1);

namespace Zlikavac32\Enum\Tests;

use Error;
use LogicException;
use PHPUnit\Framework\TestCase;
use Zlikavac32\Enum\Tests\Fixtures\AbstractEnumWithoutEnumerate;
use Zlikavac32\Enum\Tests\Fixtures\DuplicateNameEnum;
use Zlikavac32\Enum\Tests\Fixtures\EnumThatDependsOnEnum;
use Zlikavac32\Enum\Tests\Fixtures\EnumThatEnumeratesToLittle;
use Zlikavac32\Enum\Tests\Fixtures\EnumThatEnumeratesToMuch;
use Zlikavac32\Enum\Tests\Fixtures\EnumThatExtendsNonAbstractEnumWithoutEnumerate;
use Zlikavac32\Enum\Tests\Fixtures\EnumThatExtendsValidObjectsEnum;
use Zlikavac32\Enum\Tests\Fixtures\EnumThatExtendsValidStringEnum;
use Zlikavac32\Enum\Tests\Fixtures\ValidEnumWithOneParent;
use Zlikavac32\Enum\Tests\Fixtures\EnumWithSomeVeryVeryLongNameA;
use Zlikavac32\Enum\Tests\Fixtures\EnumWithSomeVeryVeryLongNameB;
use Zlikavac32\Enum\Tests\Fixtures\InvalidAliasNameEnum;
use Zlikavac32\Enum\Tests\Fixtures\InvalidObjectAliasEnumerationObjectsEnum;
use Zlikavac32\Enum\Tests\Fixtures\InvalidOverrideConstructorEnum;
use Zlikavac32\Enum\Tests\Fixtures\NameWithinEnumerateEnum;
use Zlikavac32\Enum\Tests\Fixtures\NoPHPDocMethodEnum;
use Zlikavac32\Enum\Tests\Fixtures\NonAbstractEnum;
use Zlikavac32\Enum\Tests\Fixtures\NonObjectEnumerationObjectsEnum;
use Zlikavac32\Enum\Tests\Fixtures\OrdinalWithinEnumerateEnum;
use Zlikavac32\Enum\Tests\Fixtures\ValidObjectsEnum;
use Zlikavac32\Enum\Tests\Fixtures\ValidStringEnum;
use Zlikavac32\Enum\Tests\Fixtures\WrongClassEnumerationObjectsEnum;
use Zlikavac32\Enum\Tests\Fixtures\ZeroLengthEnumerationObjectsEnum;
use function iterator_to_array;
use function json_encode;

class EnumTest extends TestCase
{
    public function testThatIdentityCheckWorks(): void
    {
        $this->assertTrue(ValidStringEnum::ENUM_A() === ValidStringEnum::ENUM_A());
        $this->assertTrue(ValidStringEnum::ENUM_B() === ValidStringEnum::ENUM_B());
        $this->assertTrue(ValidStringEnum::ENUM_A() !== ValidStringEnum::ENUM_B());
    }

    public function testThatEqualityCheckWorks(): void
    {
        $this->assertTrue(ValidStringEnum::ENUM_A() == ValidStringEnum::ENUM_A());
        $this->assertTrue(ValidStringEnum::ENUM_B() == ValidStringEnum::ENUM_B());
        $this->assertTrue(ValidStringEnum::ENUM_A() != ValidStringEnum::ENUM_B());
    }

    public function testThatAnyOfReturnsTrue(): void
    {
        $this->assertTrue(ValidStringEnum::ENUM_A()->isAnyOf(ValidStringEnum::ENUM_B(), ValidStringEnum::ENUM_A()));
    }

    public function testThatAnyOfReturnsFalse(): void
    {
        $this->assertFalse(ValidStringEnum::ENUM_A()->isAnyOf(ValidStringEnum::ENUM_B()));
    }

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

    public function testThatOrdinalThrowExceptionUntilValueIsDefined(): void
    {
        $this->expectException(Error::class);
        $this->expectExceptionMessage('Typed property Zlikavac32\Enum\Enum::$ordinal must not be accessed before initializatio');

        OrdinalWithinEnumerateEnum::ENUM_A();
    }

    public function testThatNameThrowExceptionUntilValueIsDefined(): void
    {
        $this->expectException(Error::class);
        $this->expectExceptionMessage('Typed property Zlikavac32\Enum\Enum::$name must not be accessed before initialization');

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

    public function testThatEnumObjectHasValidJsonEncodeRepresentation(): void
    {
        $this->assertSame(
            '"ENUM_A"',
            json_encode(ValidObjectsEnum::ENUM_A())
        );
    }

    public function testThatCloneIsNotSupported(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Cloning enum element is not allowed');

        clone ValidObjectsEnum::ENUM_A();
    }

    public function testThatValueOfReturnsRequestedEnum(): void
    {
        $this->assertSame(ValidObjectsEnum::ENUM_A(), ValidObjectsEnum::valueOf('ENUM_A'));
    }

    public function testThatValueOfThrowsExceptionWhenEnumDoesNotExist(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Enum element I_DONT_EXIST missing in Zlikavac32\Enum\Tests\Fixtures\ValidObjectsEnum');

        ValidObjectsEnum::valueOf('I_DONT_EXIST');
    }

    public function testThatContainsReturnsTrueForExistingEnum(): void
    {
        $this->assertTrue(ValidObjectsEnum::contains('ENUM_A'));
    }

    public function testThatContainsReturnsFalseForNonExistingEnum(): void
    {
        $this->assertFalse(ValidObjectsEnum::contains('I_DONT_EXIST'));
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

    public function testThatEnumObjectCallsMustBeWithoutArguments(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('No argument must be provided when calling Zlikavac32\Enum\Tests\Fixtures\ValidObjectsEnum::ENUM_B');

        ValidObjectsEnum::ENUM_B(0);
    }

    public function testThatZeroLengthEnumerationObjectConfigurationThrowsException(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Enum Zlikavac32\Enum\Tests\Fixtures\ZeroLengthEnumerationObjectsEnum must define at least one element');

        ZeroLengthEnumerationObjectsEnum::iterator();
    }

    public function testThatNonAbstractEnumThrowsException(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Enum Zlikavac32\Enum\Tests\Fixtures\NonAbstractEnum must be declared as abstract');

        NonAbstractEnum::ENUM_A();
    }

    public function testThatInvalidAliasNameThrowsException(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Element name "3ID" does not match pattern /^[a-zA-Z_][a-zA-Z_0-9]*$/');

        InvalidAliasNameEnum::values();
    }

    public function testThatDefaultEnumerationObjectConfigurationThrowsException(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('You must provide PHPDoc for static methods in your enum class Zlikavac32\Enum\Tests\Fixtures\NoPHPDocMethodEnum');

        NoPHPDocMethodEnum::iterator();
    }

    public function testThatAccessingNonExistingEnumThrowsException(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Enum element I_DONT_EXIST missing in Zlikavac32\Enum\Tests\Fixtures\ValidObjectsEnum');

        ValidObjectsEnum::I_DONT_EXIST();
    }

    public function testThatInvalidObjectAliasThrowsException(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Element name 0 in enum Zlikavac32\Enum\Tests\Fixtures\InvalidObjectAliasEnumerationObjectsEnum is not valid');

        InvalidObjectAliasEnumerationObjectsEnum::iterator();
    }

    public function testThatWrongEnumInstanceThrowsException(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Enum element object in enum Zlikavac32\Enum\Tests\Fixtures\WrongClassEnumerationObjectsEnum must be an instance of Zlikavac32\Enum\Tests\Fixtures\WrongClassEnumerationObjectsEnum (an instance of Zlikavac32\Enum\Tests\Fixtures\AWrongClassEnumerationObjectsDummyEnum received)');

        WrongClassEnumerationObjectsEnum::iterator();
    }

    public function testThatObjectEnumThrowsException(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Enum element object in enum Zlikavac32\Enum\Tests\Fixtures\NonObjectEnumerationObjectsEnum must be an instance of Zlikavac32\Enum\Tests\Fixtures\NonObjectEnumerationObjectsEnum (integer received)');

        NonObjectEnumerationObjectsEnum::iterator();
    }

    public function testThatSetStateThrowsException(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Serialization/deserialization of enum element is not allowed');

        ValidStringEnum::__set_state();
    }

    public function testThatSleepThrowsException(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Serialization/deserialization of enum element is not allowed');

        ValidStringEnum::ENUM_A()->__sleep();
    }

    public function testThatWakeupThrowsException(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Serialization/deserialization of enum element is not allowed');

        ValidStringEnum::ENUM_A()->__wakeup();
    }

    public function testThatSerializeThrowsException(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Serialization/deserialization of enum element is not allowed');

        ValidStringEnum::ENUM_A()->serialize();
    }

    public function testThatUnserializeThrowsException(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Serialization/deserialization of enum element is not allowed');

        ValidStringEnum::ENUM_A()->unserialize('');
    }

    public function testThatConstructMustBeCalledForName(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('It seems that enum is not correctly initialized. Did you forget to call parent::__construct() in enum Zlikavac32\Enum\Tests\Fixtures\InvalidOverrideConstructorEnum?');

        (new InvalidOverrideConstructorEnum())->name();
    }

    public function testThatConstructMustBeCalledForOrdinal(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('It seems that enum is not correctly initialized. Did you forget to call parent::__construct() in enum Zlikavac32\Enum\Tests\Fixtures\InvalidOverrideConstructorEnum?');

        (new InvalidOverrideConstructorEnum())->ordinal();
    }

    public function testThatConstructMustBeCalledForIsAnyOf(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('It seems that enum is not correctly initialized. Did you forget to call parent::__construct() in enum Zlikavac32\Enum\Tests\Fixtures\InvalidOverrideConstructorEnum?');

        (new InvalidOverrideConstructorEnum())->isAnyOf();
    }

    public function testThatNameThrowsExceptionWhenNotConstructedCorrectly(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('It seems you tried to manually create enum outside of enumerate() method for enum Zlikavac32\Enum\Tests\Fixtures\NonAbstractEnum');

        (new NonAbstractEnum())->name();
    }

    public function testThatOrdinalThrowsExceptionWhenNotConstructedCorrectly(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('It seems you tried to manually create enum outside of enumerate() method for enum Zlikavac32\Enum\Tests\Fixtures\NonAbstractEnum');

        (new NonAbstractEnum())->ordinal();
    }

    public function testThatToStringThrowsExceptionWhenNotConstructedCorrectly(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('It seems you tried to manually create enum outside of enumerate() method for enum Zlikavac32\Enum\Tests\Fixtures\NonAbstractEnum');

        (new NonAbstractEnum())->__toString();
    }

    public function testThatDuplicateElementThrowsException(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Duplicate element ENUM_A exists in enum Zlikavac32\Enum\Tests\Fixtures\DuplicateNameEnum');

        DuplicateNameEnum::ENUM_A();
    }

    public function testThatWorkaroundForPHPEvalBugWorks(): void
    {
        try {
            EnumWithSomeVeryVeryLongNameA::ENUM_A();
            $this->assertInstanceOf(
                EnumWithSomeVeryVeryLongNameB::class,
                EnumWithSomeVeryVeryLongNameB::ENUM_A()
            );
        } catch (LogicException $e) {
            $this->fail('Workaround no longer works');
        }
    }

    public function testThatNonDefiningEnumClassInChainMustNotDefinePHPDoc(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Enum Zlikavac32\Enum\Tests\Fixtures\EnumThatExtendsValidStringEnum extends Zlikavac32\Enum\Tests\Fixtures\ValidStringEnum which already defines enum names in PHPDoc');

        EnumThatExtendsValidStringEnum::ENUM_A();
    }

    public function testThatNonDefiningEnumClassInChainMustNotDefineEnumerate(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Enum Zlikavac32\Enum\Tests\Fixtures\EnumThatExtendsValidObjectsEnum extends Zlikavac32\Enum\Tests\Fixtures\ValidObjectsEnum which already defines enumerate() method');

        EnumThatExtendsValidObjectsEnum::ENUM_A();
    }

    public function testThatNonDefiningEnumClassInChainMustBeAbstract(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Class Zlikavac32\Enum\Tests\Fixtures\NonAbstractEnumWithoutEnumerate must be also abstract (since Zlikavac32\Enum\Tests\Fixtures\EnumThatExtendsNonAbstractEnumWithoutEnumerate extends it)');

        EnumThatExtendsNonAbstractEnumWithoutEnumerate::ENUM_A();
    }

    public function testThatEnumWithAbstractParentCanBeConstructed(): void
    {
        $this->assertTrue(ValidEnumWithOneParent::ENUM_A() instanceof AbstractEnumWithoutEnumerate);
        $this->assertTrue(ValidEnumWithOneParent::ENUM_A() instanceof ValidEnumWithOneParent);
    }

    public function testThatExceptionIsThrowForEnumThatEnumeratesToMuch(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Enum Zlikavac32\Enum\Tests\Fixtures\EnumThatEnumeratesToMuch enumerates [ENUM_B, ENUM_C] which are not found in PHPDoc');

        EnumThatEnumeratesToMuch::ENUM_A();
    }

    public function testThatExceptionIsThrowForEnumThatEnumeratesToLittle(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Enum Zlikavac32\Enum\Tests\Fixtures\EnumThatEnumeratesToLittle does not enumerate [ENUM_B, ENUM_C] which are found in PHPDoc');

        EnumThatEnumeratesToLittle::ENUM_A();
    }

    public function testThatCarriageReturnInMethodNameIsNotCaptured(): void
    {
        // to avoid editor accidentally deleting \r, this enum fixture is evaluated
        $code = <<<'PHP'
namespace Zlikavac32\Enum\Tests\Fixtures;

use Zlikavac32\Enum\Enum;

/**
 * @method static EnumWithCarriageReturnInMethod ENUM_A%s
 */
abstract class EnumWithCarriageReturnInMethod extends Enum
{

}
PHP;

        eval(sprintf($code, "\r"));

        $this->assertSame(
            0,
            \Zlikavac32\Enum\Tests\Fixtures\EnumWithCarriageReturnInMethod::ENUM_A()
                            ->ordinal()
        );
    }
}
