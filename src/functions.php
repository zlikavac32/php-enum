<?php

declare(strict_types=1);

namespace Zlikavac32\Enum;

use LogicException;
use ReflectionClass;
use ReflectionException;
use function get_class;
use function gettype;
use function is_object;
use function is_string;
use function is_subclass_of;
use function preg_match;
use function sprintf;

/**
 * @throws LogicException If value in $fqn is not a class extending Zlikavac32\Enum\Enum
 */
function assertFqnIsEnumClass(string $fqn): void
{
    if (is_subclass_of($fqn, Enum::class)) {
        return ;
    }

    throw new LogicException(sprintf('%s does not have %s as it\'s parent', $fqn, Enum::class));
}

/**
 * Checks for library specific constraints put on an enum class.
 *
 * Currently they are:
 *   - enum class must be abstract
 *   - no class between enum class and Zlikavac32\Enum\Enum (both exclusive) can implement enumerate() method
 *   - every class in the chain must be abstract
 *
 * @throws ReflectionException If something went wrong in reflection API
 * @throws LogicException If some condition is not fulfilled
 */
function assertEnumClassAdheresConstraints(string $fqn): void {
    assertEnumClassIsAbstract($fqn);
    assertNoParentHasEnumerateMethodForClass($fqn);
    assertNoParentHasPHPDocMethodForClass($fqn);
}

/**
 * Functions asserts that parents (except for Zlikavac32\Enum\Enum) are abstract and do not define enumerate() method.
 *
 * @throws ReflectionException If something went wrong in reflection API
 * @throws LogicException If one of parents is not abstract
 * @throws LogicException If one of parents defines enumerate() method
 */
// @todo: change name of this to something more meaningful
function assertNoParentHasEnumerateMethodForClass(string $fqn): void {
    foreach (class_parents($fqn) as $parent) {
        $reflectionClass = new ReflectionClass($parent);
        $reflectionMethod = $reflectionClass->getMethod('enumerate');

        $declaringClass = $reflectionMethod->getDeclaringClass();

        if ($declaringClass->name !== Enum::class) {
            throw new LogicException(
                sprintf('Enum %s extends %s which already defines enumerate() method', $fqn, $parent)
            );
        }

        if (!$reflectionClass->isAbstract()) {
            throw new LogicException(
                sprintf(
                    'Class %s must be also abstract (since %s extends it)',
                    $parent,
                    $fqn
                )
            );
        }
    }
}

function assertNoParentHasPHPDocMethodForClass(string $fqn): void {
    foreach (class_parents($fqn) as $parent) {
        $reflectionClass = new ReflectionClass($parent);

        if (
            $reflectionClass->getDocComment()
            &&
            preg_match('/@method\s+static/', $reflectionClass->getDocComment())
        ) {
            throw new LogicException(
                sprintf('Enum %s extends %s which already defines enum names in PHPDoc', $fqn, $parent)
            );
        }
    }
}

/**
 * @throws LogicException If pattern "/^[a-zA-Z_][a-zA-Z_0-9]*$/" is not satisfied
 */
function assertValidNamePattern(string $name): void
{
    $pattern = '/^[a-zA-Z_][a-zA-Z_0-9]*$/';

    if (preg_match($pattern, $name)) {
        return ;
    }

    throw new LogicException(sprintf('Element name "%s" does not match pattern %s', $name, $pattern));
}

/**
 * Checks that enum class is abstract.
 *
 * @throws ReflectionException If something went wrong in reflection API
 * @throws LogicException If enum class is not abstract
 */
// @todo: Remove this, and merge with assertNoParentHasEnumerateMethodForClass()
function assertEnumClassIsAbstract(string $fqn): void
{
    if ((new ReflectionClass($fqn))->isAbstract()) {
        return ;
    }

    throw new LogicException(sprintf('Enum %s must be declared as abstract', $fqn));
}

/**
 * Checks whether a collection of enum objects represents valid collection for enum class in $class. Parameter
 * $enumClassFqn is same as $class and will be removed later.
 *
 * Rules that must be satisfied:
 *   - element name must be valid string
 *   - enum object must be instance of $class
 *
 * @throws LogicException If some condition is not fulfilled
 */
function assertValidEnumCollection(string $class, array $enumCollection, string $enumClassFqn): void
{
    foreach ($enumCollection as $elementName => $object) {
        assertElementNameIsString($class, $elementName);
        assertValidNamePattern($elementName);
        assertValidEnumElementObjectType($class, $object, $enumClassFqn);
    }
}

/**
 * Asserts that value in $name is string.
 *
 * @param $name
 *
 * @throws LogicException If value in $name is not string
 */
function assertElementNameIsString(string $class, $name): void
{
    if (is_string($name)) {
        return;
    }

    if (is_object($name)) {
        $formattedElementName = sprintf('(object instance of %s)', get_class($name));
    } else {
        $formattedElementName = $name;
    }

    throw new LogicException(
        sprintf('Element name %s in enum %s is not valid', $formattedElementName, $class)
    );
}

/**
 * Asserts that $object is instance off $class. Parameter $enumClassFqn must (although is not enforced currently) have
 * same value as $class. It will be removed in the future.
 *
 * @param mixed $object
 *
 * @throws LogicException If $object is not an instance of $class
 */
function assertValidEnumElementObjectType(string $class, $object, string $enumClassFqn): void
{
    if ($object instanceof $class) {
        return;
    }

    $resolvedType = gettype($object);

    if ('object' === $resolvedType) {
        $resolvedType = 'an instance of ' . get_class($object);
    }

    throw new LogicException(
        sprintf(
            'Enum element object in enum %s must be an instance of %s (%s received)',
            $enumClassFqn,
            $class,
            $resolvedType
        )
    );
}
