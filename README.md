# PHP Enum

[![Build Status](https://travis-ci.org/zlikavac32/php-enum.svg?branch=master)](https://travis-ci.org/zlikavac32/php-enum)

**This is currently alpha-to-beta implementation.**

This library aims to make your everyday enumeration use in PHP easier.

## Table of contents

1. [What are enums and when to use them?](#what-are-enums-and-when-to-use-them)
1. [Installation](#installation)
1. [Features](#features)
    1. [Type-hinting](#type-hinting)
    1. [Polymorphism](#polymorphism)
    1. [Identity check](#identity check)
1. [Usage](#usage)
1. [Restrictions](#restrictions)
    1. [No serialisation](#no-serialisation)
    1. [No cloning](#no-cloning)
1. [Limitations](#limitations)
1. [Examples](#examples)
1. [Reasoning behind this library](#reasoning-behind-this-library)

## What are enums and when to use them?

To reference [Wikipedia](http://wikipedia.org/wiki/Enumerated_type):
> In computer programming, an enumerated type (also called enumeration, enum, or factor in the R programming language, and a categorical variable in statistics) is a data type consisting of a set of named values called elements, members, enumeral, or enumerators of the type. [...] In other words, an enumerated type has values that are different from each other, and that can be compared and assigned, but are not specified by the programmer as having any particular concrete representation in the computer's memory; compilers and interpreters can represent them arbitrarily.

You can think of enums as constants on steroids and the main benefit of using enums is that you hide internal representation or in other words, enums are types themselves. Constants on the other hand inherit type of the assigned value. On top of that, this implementation offers polymorphic behaviour (inspired by [Java Enum](https://docs.oracle.com/javase/8/docs/api/java/lang/Enum.html)).

That being said, if you want to logically group similar items, use enums and for other things use constants. By using enums you can type-hint arguments and return values and delegate various checks to the PHP engine.

## Installation

Recommended installation is through Composer. This is still alpha-to-beta version so branch `dev-master` is used. That will change in the near future.

```
composer require zlikavac32/php-enum:dev-master
```

## Features

Custom enum implementations (emulations) are nothing new in the PHP world since PHP lacks a native enum support. This library aims to provide correct enumeration support in PHP.

### Type-hinting

You can type-hint your arguments and return values just like with any other class (`function getNextDay(WeekDay $day): Day`). This, sort of (check [Limitations](#limitations)), guarantees that you'll always get valid enumeration object.

### Polymorphism

Behind the curtain, every enumeration is an instance of the defined enumeration class. That enables you to define your own constructor, override some methods or even define abstract methods that are later implemented in different enum variations.

### Identity check

Every call to enum object guarantees to return same instance every time it is called. That way you can use `===` to check whether instances are the same or not.

## Usage

Create an abstract class that will represent your enum and let it extend `\Zlikavac32\Enum\Enum`. 

You must implement method `protected static function enumerate(): array` that must return either an array of strings that will represent enumeration names or associative array of enumeration object instances that are indexed by their respective name. 

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
            'YES', 'NO'
        ];
    }
}
```

Once you have your enum class defined, you can access each defined enum object by calling `YourEnumClass::YOUR_ENUM_NAME()` or in the example above, `YesNo::YES()` and `YesNo::NO()`.

Your class is not required to be abstract, but it's a suggestion to prevent manual enum instantiation.

Other suggestion is to use constant like convention when defining enum name where you use upper-cased letters and underscore as a separator.

Every call to the same enum will return that same object so you can safely use identity operator.

Since enums are created using static method, it's recommended to type-hint your class with existing static methods using `@method static YourEnumClass YOUR_ENUM_NAME`.

## Restrictions

To mitigate wrong usage and sleepless nights in debugging, some restrictions are placed. They exist not because I want them to, but to serve as an early warning that something could go wrong in the long run. If you try really hard, you can still avoid them but then what's the purpose of this library to you?

### No serialisation

In order to try to avoid misuse as much as possible, you can not serialize/unserialize enumeration objects. With normal library usage, this preserves identity check which could unintentionally be broken.

### No cloning

The reasoning behind this is the same as with serialisation.

## Limitations

As with any emulation, there are some limitations. Main limitation is that this library can not guarantee that every object is one of the valid enum instances. If you try really hard to find an edge case, you'll succeed, but this is not the idea because this can't be fixed in the first place. If a language does not put some restrictions, user land implementations can hardly do that. 

Since you can always extend existing enum, you can send it somewhere where valid enum is expected. On the other hand, you can never alter existing enums which is one of the goals.

Other limitation is that everything is done during runtime so some static analysis my report issues or some compiler checks may not be performed that would otherwise be performed if PHP had native support. To combat that, you can hint methods in doc-block comments.

## Examples

You can see more examples with code comments in [examples](/examples).

## Reasoning behind this library

I find this way quite intuitive and a good enum direction. You can misuse everything if you want to. This does not mean that you should not use this library. It means that this will make your easier if you use it with these limitations on your mind.

Enums are not just groups of constants, they are types themselves and can exhibit polymorphic behaviour. This is what this library aims to provide. Use it with care and it should be more than helpful.

Final goal is to see something similar as a native PHP enum implementation, but for now, one can just dream, right?
