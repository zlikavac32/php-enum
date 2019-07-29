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
use function array_values;
use function count;
use function get_class;
use function get_parent_class;
use function sprintf;

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

    public function __construct()
    {
        $this->assertValidConstructionContext();
        $this->correctlyInitialized = true;
    }

    private function assertValidConstructionContext(): void
    {
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

    /**
     * @throws Throwable
     */
    final public static function __set_state()
    {
        throw self::createNoSerializeUnserializeException();
    }

    /**
     * @throws Throwable
     */
    final public function __wakeup()
    {
        throw self::createNoSerializeUnserializeException();
    }

    /**
     * @throws Throwable
     */
    final public function __sleep()
    {
        throw self::createNoSerializeUnserializeException();
    }

    /**
     * @return string|void
     * @throws Throwable
     */
    final public function serialize()
    {
        throw self::createNoSerializeUnserializeException();
    }

    /**
     * @param string $serialized
     *
     * @throws Throwable
     */
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
        return [];
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
        assertEnumClassAdheresConstraints($class);

        $enumNames = self::resolveMethodsFromDocblock($class);

        /* @var Enum[] $enumObjects */
        $enumObjects = static::enumerate();

        $objects = self::normalizeElementsArray($class, $enumNames, $enumObjects);

        self::populateEnumObjectProperties($objects);

        return $objects;
    }

    private static function resolveMethodsFromDocblock(string $class): array
    {
        $docBlock = (new ReflectionClass($class))->getDocComment();

        if (!$docBlock) {
            throw new LogicException(
                sprintf(
                    'You must provide PHPDoc for static methods in your enum class %s',
                    static::class
                )
            );
        }

        $regex = '/@method\s+static\s+[^\s]+\s+(\w+)\s*(?:\(\))?\s*$/m';

        if (!preg_match_all($regex, $docBlock, $matches, PREG_SET_ORDER)) {
            throw new LogicException(sprintf('Enum %s must define at least one element', $class));
        }

        $enumNames = [];

        foreach ($matches as $match) {
            assertValidNamePattern($match[1]);

            $enumNames[] = $match[1];
        }

        return $enumNames;
    }

    private static function normalizeElementsArray(string $class, array $enumNames, array $enumObjects): array
    {
        if (count($enumObjects) === 0) {
            return self::createDynamicEnumElementObjects($class, $enumNames);
        }

        assertValidEnumCollection($class, $enumObjects, $class);

        $enumeratedEnumNames = array_keys($enumObjects);

        $extraEnumeratedKeys = array_diff($enumeratedEnumNames, $enumNames);

        if (count($extraEnumeratedKeys) > 0) {
            throw new LogicException(sprintf('Enum %s enumerates [%s] which are not found in PHPDoc', $class, implode(', ', $extraEnumeratedKeys)));
        }

        $missingKeysInEnumeration = array_diff($enumNames, $enumeratedEnumNames);

        if (count($missingKeysInEnumeration) > 0) {
            throw new LogicException(sprintf('Enum %s does not enumerate [%s] which are found in PHPDoc', $class, implode(', ', $missingKeysInEnumeration)));
        }

        return $enumObjects;
    }

    private static function populateEnumObjectProperties(array $objects): void
    {
        $ordinal = 0;

        foreach ($objects as $elementName => $object) {
            $object->ordinal = $ordinal++;
            $object->name = $elementName;
        }
    }

    private static function createDynamicEnumElementObjects(string $class, array $enumNames): array
    {
        $defineClasses = '';
        $createObjects = '';

        $usedNames = [];

        foreach ($enumNames as $enumName) {
            assertElementNameIsString($class, $enumName);
            assertValidNamePattern($enumName);

            if (isset($usedNames[$enumName])) {
                throw new LogicException(sprintf('Duplicate element %s exists in enum %s', $enumName, $class));
            }

            $proxyClassName = 'Proxy_' . sha1($enumName . $class);

            $defineClasses .= sprintf('class %s extends \\%s {};', $proxyClassName, $class);
            $createObjects .= sprintf('"%s" => new %s(),', $enumName, $proxyClassName);

            $usedNames[$enumName] = true;
        }

        $evalString = sprintf('namespace Zlikavac32\\Dynamic\\Proxy; %s return [%s];', $defineClasses, $createObjects);

        return eval($evalString);
    }
}
