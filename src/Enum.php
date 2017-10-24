<?php

declare(strict_types=1);

namespace Zlikavac32\Enum;

use ArrayIterator;
use InvalidArgumentException;
use Iterator;
use LogicException;
use Serializable;
use Throwable;

abstract class Enum implements Serializable
{
    /**
     * @var Enum[][]
     */
    private static $existingEnums = [];

    /**
     * @var int
     */
    private $ordinal;

    /**
     * @var string
     */
    private $name;

    final public function ordinal(): int
    {
        if (null === $this->ordinal) {
            throw new LogicException('You can not retrieve ordinal within enumerate()');
        }

        return $this->ordinal;
    }

    final public function name(): string
    {
        if (null === $this->name) {
            throw new LogicException('You can not retrieve name within enumerate()');
        }

        return $this->name;
    }

    final public function __clone()
    {
        throw new LogicException('Cloning enum object is not allowed');
    }

    final public function __set_state()
    {
        throw $this->createNoSerializeUnserializeException();
    }

    final public function __wakeup()
    {
        throw $this->createNoSerializeUnserializeException();
    }

    final public function __sleep()
    {
        throw $this->createNoSerializeUnserializeException();
    }

    final public function serialize()
    {
        throw $this->createNoSerializeUnserializeException();
    }

    final public function unserialize($serialized): object
    {
        throw $this->createNoSerializeUnserializeException();
    }

    private function createNoSerializeUnserializeException(): Throwable
    {
        return new LogicException('Serialization/deserialization of enum object is not allowed');
    }

    public function __toString(): string
    {
        return $this->name();
    }

    /**
     * @return Iterator|static[] Enum items in order they are defined
     */
    final public static function iterator(): Iterator
    {
        return new ArrayIterator(self::values());
    }

    /**
     * @return static[] Enum items in order they are defined
     */
    final public static function values(): array
    {
        return array_values(self::retrieveCurrentContextEnumerations());
    }

    /**
     * @param string $name
     *
     * @return static
     */
    public static function valueOf(string $name): Enum
    {
        return self::__callStatic($name, []);
    }

    /**
     * @param $name
     * @param $arguments
     *
     * @return static
     */
    final public static function __callStatic($name, $arguments): Enum
    {
        if (count($arguments) > 0) {
            throw new InvalidArgumentException(
                sprintf('No argument must be provided when calling %s::%s', static::class, $name)
            );
        }

        $objects = self::retrieveCurrentContextEnumerations();

        if (!isset($objects[$name])) {
            throw new EnumNotFoundException($name, static::class);
        }

        return $objects[$name];
    }

    protected static function enumerate(): array
    {
        throw new LogicException(
            sprintf(
                'You must provide protected static function enumerate(): array method in your enum class %s',
                static::class
            )
        );
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
        /* @var Enum[]|string[] $objectsOrEnumNames */
        $objectsOrEnumNames = static::enumerate();

        if (count($objectsOrEnumNames) === 0) {
            throw new LogicException(sprintf('Enumeration objects array in class %s can not be empty', static::class));
        }

        if (self::collectionRepresentsSimpleEnumeration($objectsOrEnumNames)) {
            $objects = self::createDynamicEnumObjects($objectsOrEnumNames);
        } else {
            self::assertValidEnumCollection($objectsOrEnumNames);

            $objects = $objectsOrEnumNames;
        }

        $i = 0;

        foreach ($objects as $alias => $object) {
            $object->ordinal = $i++;
            $object->name = $alias;
        }

        return $objects;
    }

    private static function collectionRepresentsSimpleEnumeration(array $objectsOrEnumNames): bool
    {
        reset($objectsOrEnumNames);
        $key = key($objectsOrEnumNames);

        return is_int($key);
    }

    private static function assertValidEnumCollection(array $enumCollection): void
    {
        foreach ($enumCollection as $alias => $object) {
            self::assertValidStringAlias($alias);
            self::assertValidEnumObject($object);
        }
    }

    private static function createDynamicEnumObjects(array $enumNames): array
    {
        $evalString = sprintf('return new class extends %s {};', static::class);
        $objects = [];
        //We don't care about the indexes whether they are strings or are they out of order
        //That may change in the future though
        foreach ($enumNames as $enumName) {
            self::assertValidStringAlias($enumName);
            //eval is in a controlled environment but I'm glad that you're careful
            $objects[$enumName] = eval($evalString);
        }

        return $objects;
    }

    private static function assertValidStringAlias($alias): void
    {
        if (is_string($alias)) {
            //For now we allow anything else although in the future some name patterns may arise
            return;
        }

        if (is_object($alias)) {
            $formattedAlias = sprintf('(object instance of %s)', get_class($alias));
        } else {
            $formattedAlias = $alias;
        }
        throw new LogicException(
            sprintf('Alias %s in enum class %s is not valid alias', $formattedAlias, static::class)
        );
    }

    private static function assertValidEnumObject($object): void
    {
        if ($object instanceof static) {
            return;
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
