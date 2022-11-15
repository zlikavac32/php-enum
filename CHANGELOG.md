# PHP-Enum changelog

## 5.0.0 (2022-11-15)

* **[CHANGED]** Renamed namespace and class from `Enum` to `ZEnum` to allow PHP 8.1

## 4.0.0 (2021-04-12)

* **[CHANGED]** Added support for PHP 8.0

## 3.0.0 (2020-04-08)

* **[CHANGED]** `\Zlikavac32\Enum\assertNoParentHasEnumerateMethodForClass()` is renamed to `\Zlikavac32\Enum\assertEnumClassParentsAdhereConstraints()`
* **[CHANGED]** `\Error` is thrown instead of the `\LogicException` if a property is accessed before it's initialized
* **[CHANGED]** Minimal supported PHP version is 7.4

## 2.0.1 (2019-07-29)

* **[FIXED]** Method regex capturing carriage return
* **[ADDED]** `\Zlikavac32\Enum\UnhandledEnumException`

## 2.0.0 (2019-03-26)

* **[ADDED]** Enum names are resolved from the class PHPDoc comment
* **[REMOVED]** Enum names can no longer be listed as string in the `enumerate()` method

## 1.4.0 (2019-03-22)

* **[REMOVED]** PHP 7.1 support

## 1.3.0 (2019-01-23)

* **[FIXED]** Avoid issue with [https://bugs.php.net/bug.php?id=73816](https://bugs.php.net/bug.php?id=73816)

## 1.2.0 (2018-11-23)

* **[CHANGED]** Enum class no longer must be immediate parent

## 1.1.1 (2018-04-29)

* **[CHANGED]** Optimized functions imports
* **[CHANGED]** Extracted few private methods into functions

## 1.1.0 (2018-04-12)

* **[NEW]** Method `final isAnyOf(Enum ...$enums): bool`
* **[NEW]** Method `final static contains(string $name): bool`

## 1.0.3 (2018-03-24)

* **[FIXED]** Workaround for [https://bugs.php.net/bug.php?id=73816](https://bugs.php.net/bug.php?id=73816)

## 1.0.2 (2018-02-14)

* **[CHANGED]** Enum implements `JsonSerializable`

## 1.0.1 (2017-11-10)

* **[CHANGED]** Standardized error messages
* **[FIXED]** `__set_state` now declared as `static`
* **[CHANGED]** Element instance must have calling enum as it first parent

## 1.0.0 (2017-10-30)

* **[CHANGED]** Added constructor in `\Zlikavac32\Enum\Enum` which is required to be called
* **[NEW]** Check for duplicate names when just listing enum elements

## 0.1.0 (2017-10-27)

* **[NEW]** First tagged version
