<?php

declare(strict_types=1);

namespace Zlikavac32\Enum;

use ArrayIterator;
use InvalidArgumentException;
use Iterator;
use LogicException;

abstract class Enum
{
    private static $existingEnums = [];

    private $ordinal = -1;

    private $name = '';

    final public function ordinal(): int
    {
        return $this->ordinal;
    }

    final public function name(): string
    {
        return $this->name;
    }

    final public function __clone()
    {
        throw new LogicException('Cloning enum object is not allowed');
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return Iterator|Enum[]
     */
    public static function iterator(): Iterator
    {
        return new ArrayIterator(array_values(self::retrieveCurrentContextEnumerations()));
    }

    public static function __callStatic($name, $arguments)
    {
        if (count($arguments) > 0) {
            throw new InvalidArgumentException(
                sprintf('No argument must be provided when calling %s::%s', static::class, $name)
            );
        }

        $objects = self::retrieveCurrentContextEnumerations();

        if (!isset($objects[$name])) {
            throw new LogicException(sprintf('Enum object %s missing in %s', $name, static::class));
        }

        return $objects[$name];
    }

    /**
     * @return Enum[]
     */
    private static function retrieveCurrentContextEnumerations(): array
    {
        $class = static::class;

        if (!isset(self::$existingEnums[$class])) {
            self::$existingEnums[$class] = self::discoverEnumerationObjects();
        }

        return self::$existingEnums[$class];
    }

    private static function discoverEnumerationObjects()
    {
        /* @var Enum[] $objects */
        $objects = static::createEnumerationObjects();

        if (count($objects) === 0) {
            throw new LogicException(sprintf('Enumeration objects array in class %s can not be empty', static::class));
        }

        $i = 0;

        foreach ($objects as $alias => $object) {
            self::assertValidAlias($alias);
            self::assertValidEnumObject($object);
            $object->ordinal = $i++;
            $object->name = $alias;
        }

        return $objects;
    }

    protected static function createEnumerationObjects(): array
    {
        throw new LogicException(
            sprintf(
                'You must provide protected static function createEnumerationObjects(): array method in your enum class %s',
                static::class
            )
        );
    }

    private static function assertValidAlias($alias): void
    {
        if (!is_string($alias)) {
            throw new LogicException(sprintf('Alias %d in enum class %s is not valid alias', $alias, static::class));
        }

        //For now we allow anything else although in the future some name patterns may arise
    }

    private static function assertValidEnumObject($object): void
    {
        if ($object instanceof static) {
            return ;
        }

        if (is_object($object)) {
            $resolvedType = 'an instance of ' . get_class($object);
        } else {
            $resolvedType = gettype($object);
        }

        throw new LogicException(
            sprintf(
                'Enum object in class %s must be an instance of %s (%s received)',
                static::class,
                Enum::class,
                $resolvedType
            )
        );
    }
}
