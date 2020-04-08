<?php

declare(strict_types=1);

namespace Zlikavac32\Enum\Tests;

use LogicException;
use PHPUnit\Framework\TestCase;
use stdClass;
use Throwable;
use Zlikavac32\Enum\Tests\Fixtures\EnumThatExtendsNonAbstractEnumWithoutEnumerate;
use Zlikavac32\Enum\Tests\Fixtures\EnumThatExtendsValidObjectsEnum;
use Zlikavac32\Enum\Tests\Fixtures\NonAbstractEnum;
use Zlikavac32\Enum\Tests\Fixtures\ValidEnumWithOneParent;
use Zlikavac32\Enum\Tests\Fixtures\ValidStringEnum;
use function sprintf;
use function Zlikavac32\Enum\assertElementNameIsString;
use function Zlikavac32\Enum\assertEnumClassIsAbstract;
use function Zlikavac32\Enum\assertFqnIsEnumClass;
use function Zlikavac32\Enum\assertEnumClassParentsAdhereConstraints;
use function Zlikavac32\Enum\assertValidEnumCollection;
use function Zlikavac32\Enum\assertValidEnumElementObjectType;
use function Zlikavac32\Enum\assertValidNamePattern;

class functionsTest extends TestCase
{

    public function testThatFqnRepresentingValidEnumClassPassesAssert(): void
    {
        try {
            assertFqnIsEnumClass(ValidStringEnum::class);

            $this->assertTrue(true);
        } catch (Throwable $e) {
            $this->failWithThrowable($e);
        }
    }

    public function testThatFqnNotRepresentingValidEnumClassDoesNotPassAssert(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('stdClass does not have Zlikavac32\Enum\Enum as it\'s parent');

        assertFqnIsEnumClass(stdClass::class);
    }

    public function testThatNoExceptionIsThrownOnValidName(): void
    {
        try {
            assertValidNamePattern('ABC');

            $this->assertTrue(true);
        } catch (Throwable $e) {
            $this->failWithThrowable($e);
        }
    }

    public function testThatExceptionIsThrownOnInvalidName(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Element name "FO*BAR" does not match pattern /^[a-zA-Z_][a-zA-Z_0-9]*$/');

        assertValidNamePattern('FO*BAR');
    }

    public function testThatNoExceptionIsThrownOnAbstractClass(): void
    {
        try {
            assertEnumClassIsAbstract(ValidStringEnum::class);

            $this->assertTrue(true);
        } catch (Throwable $e) {
            $this->failWithThrowable($e);
        }
    }

    public function testThatExceptionIsThrownOnNonAbstractClass(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Enum Zlikavac32\Enum\Tests\Fixtures\NonAbstractEnum must be declared as abstract');

        assertEnumClassIsAbstract(NonAbstractEnum::class);
    }

    public function testThatNoExceptionIsThrownWhenElementNameIsString(): void
    {
        try {
            assertElementNameIsString(ValidStringEnum::class, 'abcd');

            $this->assertTrue(true);
        } catch (Throwable $e) {
            $this->failWithThrowable($e);
        }
    }

    public function testThatExceptionIsThrownWhenElementNameIsObject(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Element name (object instance of stdClass) in enum Zlikavac32\Enum\Tests\Fixtures\ValidStringEnum is not valid');

        assertElementNameIsString(ValidStringEnum::class, new stdClass());
    }

    public function testThatExceptionIsThrownWhenElementNameIsScalar(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Element name 12467 in enum Zlikavac32\Enum\Tests\Fixtures\ValidStringEnum is not valid');
        assertElementNameIsString(ValidStringEnum::class, 12467);
    }

    public function testThatNoExceptionIsThrownWhenElementIsValidClassInstance(): void
    {
        try {
            assertValidEnumElementObjectType(ValidStringEnum::class, ValidStringEnum::ENUM_A(), ValidStringEnum::class);

            $this->assertTrue(true);
        } catch (Throwable $e) {
            $this->failWithThrowable($e);
        }
    }

    public function testThatExceptionIsThrownWhenElementIsInvalidClassInstance(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Enum element object in enum Zlikavac32\Enum\Tests\Fixtures\ValidStringEnum must be an instance of Zlikavac32\Enum\Tests\Fixtures\ValidStringEnum (an instance of stdClass received)');

        assertValidEnumElementObjectType(ValidStringEnum::class, new stdClass(), ValidStringEnum::class);
    }

    public function testThatExceptionIsThrownWhenElementIsScalar(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Enum element object in enum Zlikavac32\Enum\Tests\Fixtures\ValidStringEnum must be an instance of Zlikavac32\Enum\Tests\Fixtures\ValidStringEnum (integer received)');

        assertValidEnumElementObjectType(ValidStringEnum::class, 12467, ValidStringEnum::class);
    }

    public function testThatNoExceptionIsThrownWhenCollectionIsValid(): void
    {
        try {
            assertValidEnumCollection(
                ValidStringEnum::class,
                ['ENUM_A' => ValidStringEnum::ENUM_A()],
                ValidStringEnum::class
            );

            $this->assertTrue(true);
        } catch (Throwable $e) {
            $this->failWithThrowable($e);
        }
    }

    public function testThatExceptionIsThrownWhenCollectionIsNotValid(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Element name 0 in enum Zlikavac32\Enum\Tests\Fixtures\ValidStringEnum is not valid');

        assertValidEnumCollection(ValidStringEnum::class, [new stdClass()], ValidStringEnum::class);
    }

    private function failWithThrowable(Throwable $e): void
    {
        $this->fail(sprintf('No exception was expected but got: %s', $e->getMessage()));
    }

    public function testThatExceptionIsThrownWhenParentHasEnumerate(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Enum Zlikavac32\Enum\Tests\Fixtures\EnumThatExtendsValidObjectsEnum extends Zlikavac32\Enum\Tests\Fixtures\ValidObjectsEnum which already defines enumerate() method');

        assertEnumClassParentsAdhereConstraints(EnumThatExtendsValidObjectsEnum::class);
    }

    public function testThatExceptionIsThrownWhenParentHasPHPDoc(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Enum Zlikavac32\Enum\Tests\Fixtures\EnumThatExtendsValidObjectsEnum extends Zlikavac32\Enum\Tests\Fixtures\ValidObjectsEnum which already defines enumerate() method');

        assertEnumClassParentsAdhereConstraints(EnumThatExtendsValidObjectsEnum::class);
    }

    public function testThatExceptionIsThrownWhenParentIsNotAbstract(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Enum Zlikavac32\Enum\Tests\Fixtures\NonAbstractEnumWithoutEnumerate must be declared as abstract');

        assertEnumClassParentsAdhereConstraints(EnumThatExtendsNonAbstractEnumWithoutEnumerate::class);
    }

    public function testThatGoodEnumWithParentPasses(): void
    {
        try {
            assertEnumClassParentsAdhereConstraints(ValidEnumWithOneParent::class);

            $this->assertTrue(true);
        } catch (Throwable $e) {
            $this->failWithThrowable($e);
        }
    }
}
