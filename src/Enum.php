<?php

declare(strict_types=1);

namespace Zlikavac32\Enum;

use ArrayIterator;
use InvalidArgumentException;
use Iterator;
use JsonSerializable;
use LogicException;
use ReflectionClass;
use Serializable;
use Throwable;

abstract class Enum implements Serializable, JsonSerializable
{
    /**
     * @var Enum[][]
     */
    private static $existingEnums = [];
    /**
     * @var bool[]
     */
    private static $enumConstructionContext = [];
    /**
     * @var int
     */
    private $ordinal;
    /**
     * @var string
     */
    private $name;

    private $correctlyInitialized = false;

    /**
     * Number of eval-s executed so far (workaround for PHP bug #73816)
     *
     * @var int
     */
    private static $evalLine = 0;

    public function __construct()
    {
        $this->assertValidConstructionContext();
        $this->correctlyInitialized = true;
    }

    private function assertValidConstructionContext(): void
    {
        //Expected usage is new class extends Enum so first parent should be our construction context
        if (isset(self::$enumConstructionContext[get_parent_class($this)])) {
            return ;
        }

        throw new LogicException(
            sprintf(
                'It seems you tried to manually create enum outside of enumerate() method for enum %s',
                get_class($this)
            )
        );
    }

    private function assertCorrectlyInitialized(): void
    {
        if ($this->correctlyInitialized) {
            return ;
        }

        throw new LogicException(
            sprintf(
                'It seems that enum is not correctly initialized. Did you forget to call parent::__construct() in enum %s?',
                get_class($this)
            )
        );
    }

    final public function ordinal(): int
    {
        $this->assertCorrectlyInitialized();

        if (null === $this->ordinal) {
            throw new LogicException('You can not retrieve ordinal within enumerate()');
        }

        return $this->ordinal;
    }

    final public function name(): string
    {
        $this->assertCorrectlyInitialized();

        if (null === $this->name) {
            throw new LogicException('You can not retrieve name within enumerate()');
        }

        return $this->name;
    }

    final public function isAnyOf(Enum ...$enums): bool
    {
        $this->assertCorrectlyInitialized();

        foreach ($enums as $enum) {
            if ($this === $enum) {
                return true;
            }
        }

        return false;
    }

    final public function __clone()
    {
        throw new LogicException('Cloning enum element is not allowed');
    }

    final public static function __set_state()
    {
        throw self::createNoSerializeUnserializeException();
    }

    final public function __wakeup()
    {
        throw self::createNoSerializeUnserializeException();
    }

    final public function __sleep()
    {
        throw self::createNoSerializeUnserializeException();
    }

    final public function serialize()
    {
        throw self::createNoSerializeUnserializeException();
    }

    final public function unserialize($serialized)
    {
        throw self::createNoSerializeUnserializeException();
    }

    private static function createNoSerializeUnserializeException(): Throwable
    {
        return new LogicException('Serialization/deserialization of enum element is not allowed');
    }

    public function __toString(): string
    {
        return $this->name();
    }

    public function jsonSerialize()
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
     * @return bool
     */
    final public static function contains(string $name): bool
    {
        return isset(self::retrieveCurrentContextEnumerations()[$name]);
    }

    /**
     * @param string $name
     *
     * @todo: make final as is stated in docs
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

        if (isset($objects[$name])) {
            return $objects[$name];
        }

        throw new EnumNotFoundException($name, static::class);
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

        self::ensureEnumsAreDiscoveredForClass($class);

        return self::$existingEnums[$class];
    }

    private static function ensureEnumsAreDiscoveredForClass(string $class)
    {
        if (isset(self::$existingEnums[$class])) {
            return ;
        }

        try {
            self::$enumConstructionContext[$class] = true;

            self::$existingEnums[$class] = self::discoverEnumerationObjectsForClass($class);
        } finally {
            unset(self::$enumConstructionContext[$class]);
        }
    }

    private static function discoverEnumerationObjectsForClass(string $class)
    {
        self::assertEnumClassIsAbstract($class);

        /* @var Enum[]|string[] $objectsOrEnumNames */
        $objectsOrEnumNames = static::enumerate();

        $objects = self::normalizeElementsArray($class, $objectsOrEnumNames);

        self::populateEnumObjectProperties($objects);

        return $objects;
    }

    private static function normalizeElementsArray(string $class, array $objectsOrEnumNames): array
    {
        if (count($objectsOrEnumNames) === 0) {
            throw new LogicException(sprintf('Enum %s must define at least one element', $class));
        }

        if (self::collectionRepresentsSimpleEnumeration($objectsOrEnumNames)) {
            return self::createDynamicEnumElementObjects($class, $objectsOrEnumNames);
        }

        self::assertValidEnumCollection($class, $objectsOrEnumNames);

        return $objectsOrEnumNames;
    }

    private static function populateEnumObjectProperties(array $objects): void
    {
        $ordinal = 0;

        foreach ($objects as $elementName => $object) {
            $object->ordinal = $ordinal++;
            $object->name = $elementName;
        }
    }

    private static function assertEnumClassIsAbstract(string $fqn): void
    {
        if ((new ReflectionClass($fqn))->isAbstract()) {
            return ;
        }

        throw new LogicException(sprintf('Enum %s must be declared as abstract', $fqn));
    }

    private static function collectionRepresentsSimpleEnumeration(array $objectsOrEnumNames): bool
    {
        reset($objectsOrEnumNames);
        $key = key($objectsOrEnumNames);

        return is_int($key);
    }

    private static function assertValidEnumCollection(string $class, array $enumCollection): void
    {
        foreach ($enumCollection as $elementName => $object) {
            self::assertElementNameIsString($class, $elementName);
            self::assertValidEnumElementObjectType($class, $object);
        }
    }

    private static function createDynamicEnumElementObjects(string $class, array $enumNames): array
    {
        $evalString = str_repeat("\n", self::$evalLine++) . sprintf('return new class extends %s {};', $class);

        $objects = [];
        //We don't care about the indexes whether they are strings or are they out of order
        //That may change in the future though
        foreach ($enumNames as $elementName) {
            self::assertElementNameIsString($class, $elementName);
            //eval is in a controlled environment but I'm glad that you're careful
            $objects[$elementName] = eval($evalString);
        }

        if (count($enumNames) === count($objects)) {
            return $objects;
        }

        throw new LogicException(sprintf('Duplicate element exists in enum %s', $class));
    }

    private static function assertElementNameIsString(string $class, $name): void
    {
        if (is_string($name)) {
            self::assertValidNamePattern($name);

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

    private static function assertValidNamePattern(string $name): void
    {
        $pattern = '/^[a-zA-Z_][a-zA-Z_0-9]*$/i';

        if (preg_match($pattern, $name)) {
            return ;
        }

        throw new LogicException(sprintf('Element name "%s" does not match pattern %s', $name, $pattern));
    }

    private static function assertValidEnumElementObjectType(string $class, $object): void
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
                static::class,
                $class,
                $resolvedType
            )
        );
    }
}
