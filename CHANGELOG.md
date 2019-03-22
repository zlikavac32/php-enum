# PHP-Enum changelog

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
