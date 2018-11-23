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
use function preg_match;
use function sprintf;

function assertEnumClassAdheresConstraints(string $fqn): void {
    assertEnumClassIsAbstract($fqn);
    assertNoParentHasEnumerateMethodForClass($fqn);
}

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

function assertValidNamePattern(string $name): void
{
    $pattern = '/^[a-zA-Z_][a-zA-Z_0-9]*$/i';

    if (preg_match($pattern, $name)) {
        return ;
    }

    throw new LogicException(sprintf('Element name "%s" does not match pattern %s', $name, $pattern));
}

/**
 * @param string $fqn
 *
 * @throws ReflectionException
 */
function assertEnumClassIsAbstract(string $fqn): void
{
    if ((new ReflectionClass($fqn))->isAbstract()) {
        return ;
    }

    throw new LogicException(sprintf('Enum %s must be declared as abstract', $fqn));
}

function assertValidEnumCollection(string $class, array $enumCollection, string $enumClassFqn): void
{
    foreach ($enumCollection as $elementName => $object) {
        assertElementNameIsString($class, $elementName);
        assertValidNamePattern($elementName);
        assertValidEnumElementObjectType($class, $object, $enumClassFqn);
    }
}

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

function assertValidEnumElementObjectType(string $class, $object, string $enumClassFqn): void
{
    if ($object instanceof $class) {
        return;
    }

    if (is_object($object)) {
        $resolvedType = 'an instance of ' . get_class($object);
    } else {
        $resolvedType = gettype($object);
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
