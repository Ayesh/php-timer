# PHP Timer

[![Latest Stable Version](https://poser.pugx.org/ayesh/php-timer/v/stable)](https://packagist.org/packages/ayesh/php-timer) [![License](https://poser.pugx.org/ayesh/php-timer/license)](https://packagist.org/packages/ayesh/php-timer)  [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Ayesh/php-timer/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Ayesh/php-timer/?branch=master) [![CI](https://github.com/Ayesh/php-timer/workflows/CI/badge.svg)](https://github.com/Ayesh/php-timer/actions)  [![codecov](https://codecov.io/gh/Ayesh/php-timer/branch/master/graph/badge.svg)](https://codecov.io/gh/Ayesh/php-timer) [![SensioLabsInsight](https://insight.sensiolabs.com/projects/54bcf54f-5087-45bf-9813-63c79a06a642/mini.png)](https://insight.sensiolabs.com/projects/54bcf54f-5087-45bf-9813-63c79a06a642) [![Too many badges](https://img.shields.io/badge/style-too_many-brightgreen.svg?style=toomany&label=badges)](https://github.com/Ayesh/php-timer)

## Synopsis
A helper class to calculate how long a particular task took.

This class is similar to phpunit/php-timer, but not a fork, nor mimic its functionality.

 - Multiple timers by a given key.
 - Read the current time elapsed without stopping the timer.
 - Stop the timer, and continue from where it left (stopwatch).
 - Dead simple API with only 4 static methods.
 - 100% unit test coverage.
 - Gets you precise time in milliseconds (with options to convert to seconds)
 - Individual `Stopwatch` class for serialization and other use cases.

## Prerequisites

 - PHP 7.2 or later.

## Installing
The simplest way would be to install using [composer](https://getcomposer.org).
```bash
    composer require ayesh/php-timer
```

If, for some reason you can't use Composer, or don't want to (oh come on!), you can integrate the class with your current `PSR-4` autoloader by mapping `Ayesh\PHP_TIMER` namespace to the repository's `src` folder.

## Usage
It is pretty simple to use the timer, with all methods being static, and allowing only 4 methods.

#### Start timer
```php
    <?php
    use Ayesh\PHP_Timer\Timer;
    Timer::start();
````
This starts the timer (actually keeps the current time stored) with the key `default`. Throughout the library, if you do not provide a specific key, this default key is used.

Alternately, you can start the timer with a given key:
```php
    Timer::start('something');
```
Once you start the time with a given key, you can use the same key to refer to that particular timer.
You can of course use PHP magic constants to make things easier:
```php
    Timer::start(__FUNCTION__);
```
Attempting to start the timer with a non-string key will throw a `\TypeError` exception.
You can call the `start` method multiple times even if the timer has started. It will not reset the timer.

#### Read timer
After starting the timer, you can read the elapsed time at any time. Reading the time will not stop the timer. You can read the timer, do some expensive calculations, and read again to get the cumulative time.
```php
    Timer::read(); // Default timer.
    Timer::read('default'); // Default timer.
    Timer::read('something'); // Timer started with key "something".
```
Attempting to read a timer that is not started will throw an `\LogicException` exception.

##### Formats
You can pass a second argument to let this library make minimal processing for you:
```php
    Timer::read('something', Timer::FORMAT_PRECISE); // 0.10180473327637
```
See the formats section below for the formats supported.
#### Stop timer
You can stop the timer anytime as well. This makes the library store the stop time, and your further `Timer::read()` calls will always return the time it took between start and stop.
```php
    Timer::stop(); // Default timer.
    Timer::stop('something'); // Timer started with key "something"
```
Attempting to stop a timer that is not started will throw an `\LogicException` exception.

#### Reset timer
By default, starting the timer after stopping it will continue it from where it left off. For example, if you have 3 seconds on the timer when you stop it, and start it again, the total time will start from 3 seconds. You can explicitly reset the timer to make it start from 0.
Resetting the timer will not make the timer start again. You need to explicitly start the timer again with a `Timer::start()` call.
```php
    Timer::reset(); // Default timer.
    Timer::reset('something');
    Timer::resetAll(); // Resets all timers.
```
## Formats
Currently, the following formats are provided:

 - `FORMAT_PRECISE`: Precise timer value, without rounding it. e.g. `0.10180473327637`
 - `FORMAT_MILLISECONDS`:  Time in milliseconds, rounded to 2 decimals.
 - `FORMAT_SECONDS`: Time in seconds, rounded to 3 decimals.
 - `FORMAT_HUMAN`: Time in human readable format, for example `1.05 minutes`.

## Examples

#### Calculate the timer one-off:
```php
    <?php
    use Ayesh\PHP_Timer\Timer;

    Timer::start();
    // do your processing here.
    $time = Timer::read('default', Timer::FORMAT_SECONDS);
    echo "Script took {$time} second(s)";
````
#### Stop watch functionality, with stop-and-go timer calculated separately.
```php
    <?php
    use Ayesh\PHP_Timer\Timer;

    Timer::start('full');

    Timer::start('laps');
    sleep(1);
    Timer::stop('laps');

    sleep(2); // This time is not calculated under 'laps'

    Timer::start('laps');
    sleep(1);
    Timer::stop('laps');

    echo Timer::read('full', Timer::FORMAT_SECONDS); // 4 seconds.
    echo "<br />";
    echo Timer::read('laps', Timer::FORMAT_SECONDS); // 2 seconds (1 + 1)
````
## Development and tests
All issues are PRs are welcome. Travis CI and PHPUnit tests are included. If you are adding new features, please make sure to add the test coverage.

## Credits
By [Ayesh Karunaratne](https://ayesh.me) and [contributors](https://github.com/Ayesh/php-timer/graphs/contributors).

kthxbye
