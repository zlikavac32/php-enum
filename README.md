# PHP Enum

[![Build Status](https://travis-ci.org/zlikavac32/php-enum.svg?branch=master)](https://travis-ci.org/zlikavac32/php-enum) [![Latest Stable Version](https://poser.pugx.org/zlikavac32/php-enum/v/stable)](https://packagist.org/packages/zlikavac32/php-enum) [![License](https://poser.pugx.org/zlikavac32/php-enum/license)](https://packagist.org/packages/zlikavac32/php-enum) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/zlikavac32/php-enum/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/zlikavac32/php-enum/?branch=master) [![Codacy Badge](https://api.codacy.com/project/badge/Grade/7934e7f641b3434ea510adad46794fbb)](https://www.codacy.com/app/zlikavac32/php-enum?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=zlikavac32/php-enum&amp;utm_campaign=Badge_Grade)

This library aims to make your everyday enumeration use in PHP easier.

## Table of contents

1. [What are enums and when to use them?](#what-are-enums-and-when-to-use-them)
1. [Installation](#installation)
1. [Features](#features)
    1. [Type-hinting](#type-hinting)
    1. [Polymorphism](#polymorphism)
    1. [Identity check](#identity-check)
1. [API](#api)
    1. [Class methods](#class-methods)
    1. [Object methods](#object-methods)
    1. [Assert functions](#assert-functions)
1. [Usage](#usage)
1. [Restrictions](#restrictions)
    1. [No serialisation](#no-serialisation)
    1. [No cloning](#no-cloning)
    1. [Reserved methods](#reserved-methods)
1. [Limitations](#limitations)
1. [Rule of thumb](#rule-of-thumb)
1. [Examples](#examples)
1. [Reasoning behind this library](#reasoning-behind-this-library)

## What are enums and when to use them?

To reference [Wikipedia](http://wikipedia.org/wiki/Enumerated_type):
> In computer programming, an enumerated type (also called enumeration, enum, or factor in the R programming language, and a categorical variable in statistics) is a data type consisting of a set of named values called elements, members, enumeral, or enumerators of the type. [...] In other words, an enumerated type has values that are different from each other, and that can be compared and assigned, but are not specified by the programmer as having any particular concrete representation in the computer's memory; compilers and interpreters can represent them arbitrarily.

You can think of enums as constants on steroids and the main benefit of using enums is that you hide internal representation or in other words, enums are types themselves. Constants on the other hand inherit type of the assigned value. On top of that, this implementation offers polymorphic behaviour (inspired by [Java Enum](https://docs.oracle.com/javase/8/docs/api/java/lang/Enum.html)).

That being said, if you want to logically group similar items, use enums and for other things use constants. By using enums you can type-hint arguments and return values and delegate various checks to the PHP engine.

## Installation

Recommended installation is through Composer.

```
composer require zlikavac32/php-enum
```

## Features

Custom enum implementations (emulations) are nothing new in the PHP world since PHP lacks a native enum support. This library aims to provide correct enumeration support in PHP.

### Type-hinting

You can type-hint your arguments and return values just like with any other class (`function getNextDay(WeekDay $day): Day`). This, sort of (check [Limitations](#limitations)), guarantees that you'll always get valid enumeration object.

### Polymorphism

Behind the curtain, every enumeration is an instance of the defined enumeration class. That enables you to define your own constructor, override some methods or even define abstract methods that are later implemented in different enum variations.

### Identity check

Every call to enum object guarantees to return same instance every time it is called. That way you can use `===` to check whether instances are the same or not.

## API

Main class is `\Zlikavac32\Enum\Enum` which serves as base enum (I'd rather have `enum` keywords, but life isn't perfect). You have to extend it and provide list of static methods in the class PHPDoc comment. Check the [Usage](#usage) section to see a real example.

This class also exposes few public static and non static methods which are listed bellow.

### Class methods

- `final valueOf(string $name): static` - returns enum object identified by the name or throws an exception if none is found
- `final contains(string $name): bool` - returns `true` if enum object identified by the name `$name` exists, `false` otherwise
- `final values(): static[]` - returns all of the defined enum objects in order they were defined
- `iterator(): Iterator<static>` - returns iterator object with all of the defined enum objects in order they were defined

### Object methods

- `__toString(): string` - returns default string representation which is the enum name itself
- `jsonSerialize(): mixed` - defaults to element name
- `final name(): string` - returns enum object name
- `final ordinal(): int` - returns ordinal number of that enum object (it's position in the collection) starting from 0
- `final isAnyOf(Enum ...$enums): bool` - returns `true` if enum object is in the `$enums` list, `false` otherwise

Other methods serve as a way to restrict inconsistent behaviour, for example, to have two distinct objects of the same enum name. Check the [Restrictions](#restrictions) section for more info.

### Assert functions

In `src/functions.php` are some helper function that could help you asserting certain input. Every function throws `\LogicException` on failed assertion. You can check the source for documentation, here is just a list of them:

- `assertFqnIsEnumClass(string $fqn): void`
- `assertEnumClassAdheresConstraints(string $fqn): void`
- `assertNoParentHasEnumerateMethodForClass(string $fqn): void`
- `assertNoParentHasPHPDocMethodForClass(string $fqn): void`
- `assertValidNamePattern(string $name): void`
- `assertEnumClassIsAbstract(string $fqn): void`
- `assertValidEnumCollection(string $class, array $enumCollection, string $enumClassFqn): void`
- `assertElementNameIsString(string $class, $name): void`
- `assertValidEnumElementObjectType(string $class, $object, string $enumClassFqn): void`

## Usage

Create an abstract class that will represent your enum and let it extend `\Zlikavac32\Enum\Enum`. 

You must list static methods in the class PHPDoc comment. Method names are used as enum names.

```
/**
 * @method static YesNo YES
 * @method static YesNo NO
 */
abstract class YesNo extends \Zlikavac32\Enum\Enum
{

}
```

Once you have your enum class defined, you can access each defined enum object by calling `YourEnumClass::YOUR_ENUM_NAME()` or in the example above, `YesNo::YES()` and `YesNo::NO()`.

Other suggestion is to use constant like convention when defining enum name where you use upper-cased letters and underscore as a separator.

Every call to the same enum will return that same object so you can safely use identity operator.

It's also possible to manually instantiate enum objects and return them as a map (instead of a list of names).

```
/**
 * @method static YesNo YES
 * @method static YesNo NO
 */
abstract class YesNo extends \Zlikavac32\Enum\Enum
{
    protected static function enumerate(): array
    {
        return [
            'YES' => new class extends YesNo {},
            'NO' => new class extends YesNo {}
        ];
    }
}
```

Note that every enum name listed in the PHPDoc comment must exist as a key in the enumerate method with a valid enum object as a value.

### More than one parent

It is possible to have more than one class between defining enum class and `\Zlikavac32\Enum\Enum` class with a few restrictions (check [Restrictions regarding inheritance](#restrictions-regarding-inheritance)).

To see an example, open [examples/hashable_enum.php](examples/hashable_enum.php).

## Restrictions

To mitigate wrong usage and sleepless nights in debugging, some restrictions are placed. They exist not because I want them to, but to serve as an early warning that something could go wrong in the long run. If you try really hard, you can still avoid them but then what's the purpose of this library to you?

### No serialisation

In order to try to avoid misuse as much as possible, you can not serialize/unserialize enumeration objects. With normal library usage, this preserves identity check which could unintentionally be broken.

### No cloning

The reasoning behind this is the same as with serialisation.

### Restrictions regarding inheritance

- no class in between can list methods in PHPDoc comment
- no class in between can define `enumerate` method
- every class in the chain must be defined as abstract
- defining enum class must be first parent for the concrete enum object

### Reserved methods

None of the public methods in `\Zlikavac32\Enum\Enum` can be used as an enum name. Check the [API](#api) section for more details.

## Limitations

As with any emulation, there are some limitations. Main limitation is that this library can not guarantee that every object is one of the valid enum instances. If you try really hard to find an edge case, you'll succeed, but this is not the idea because this can't be fixed in the first place. If a language does not put some restrictions, user land implementations can hardly do that. 

Since you can always extend existing enum, you can send it somewhere where valid enum is expected and I don't think that for now there is a way to restrict that. Non final class is needed to make enum objects and therefore, no restrictions that can be done. Even if they could, they could be avoided in a same way. 

On the other hand, you can never alter existing enums which is one of the goals. 

Other limitation is that everything is done during runtime so some static analysis my report issues or some compiler checks may not be performed that would otherwise be performed if PHP had native support. To combat that, you can hint methods in doc-block comments.

## Rule of thumb

Used correctly, this library should make your life easier and your code more readable and more sense. Only thing you should avoid doing is extending (and constructing) your enum class outside of the `enumerate()` method. 

Don't make your enums complex. If you need a service for your enum to work, you may not need an enum or that logic does not belong here.

This is not a solution to the every problem. If you have a group of items that semantically belong together and have a common "type" like, `Gender` or `WorldSide`, make them an enum. If your items contain arbitrary values that tend to change like `WORKER_WAIT_TIME`, let them be constants. They mean different things.

So to recap, enums carry a meaning with them, constants don't.

## Examples

You can see more examples with code comments in [examples](/examples).

## Reasoning behind this library

I find this way quite intuitive and a good enum direction. You can misuse everything if you want to. This does not mean that you should not use this library. It means that this will make your easier if you use it with these limitations on your mind.

Enums are not just groups of constants, they are types themselves and can exhibit polymorphic behaviour. This is what this library aims to provide. Use it with care and it should be more than helpful.

Final goal is to see something similar as a native PHP enum implementation, but for now, one can just dream, right?
