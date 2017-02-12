# PHP Timer

[![Latest Stable Version](https://poser.pugx.org/ayesh/php-timer/v/stable)](https://packagist.org/packages/ayesh/php-timer) [![License](https://poser.pugx.org/ayesh/php-timer/license)](https://packagist.org/packages/ayesh/php-timer)  [![Code Climate](https://codeclimate.com/github/Ayesh/php-timer/badges/gpa.svg)](https://codeclimate.com/github/Ayesh/php-timer) [![Build Status](https://travis-ci.org/Ayesh/php-timer.svg?branch=master)](https://travis-ci.org/Ayesh/php-timer)  [![codecov](https://codecov.io/gh/Ayesh/php-timer/branch/master/graph/badge.svg)](https://codecov.io/gh/Ayesh/php-timer) [![SensioLabsInsight](https://insight.sensiolabs.com/projects/54bcf54f-5087-45bf-9813-63c79a06a642/mini.png)](https://insight.sensiolabs.com/projects/54bcf54f-5087-45bf-9813-63c79a06a642) [![Too many badges](https://img.shields.io/badge/style-too_many-brightgreen.svg?style=toomany&label=badges)](https://github.com/Ayesh/php-timer)

## Synopsis
A helper class to calculate how long a particular task took. 

This class is similar to phpunit/php-timer, but not a fork, nor mimic its functionality. 

 - Multiple timers by a given key. 
 - Read the current time elapsed without stopping the timer. 
 - Stop the timer, and continue from where it left (stopwatch).
 - Dead simple API with only 4 static methods.
 - 100% unit test coverage.
 - Gets you precise time in milliseconds (with options to convert to seconds)

## Prerequisites

 - PHP 7.*.
 
## Installing
The simplest way would be to install using [composer](https://getcomposer.org). 

    composer require ayesh/php-timer
    
If, for some reason you can't use Composer, or don't want to (oh come on!), you can integrate the class with your current `PSR-4` autoloader by mapping `Ayesh\PHP_TIMER` namespace to the repository's `src` folder. 

##Usage
It is pretty simple to use the timer, with all methods being static, and allowing only 4 methods. 

####Start timer

    use Ayesh\PHP_Timer\Timer;
    Timer::start();
This starts the timer (actually keeps the current time stored) with the key `default`. Throughout the library, if you do not provide a specific key, this default key is used. 

Alternately, you can start the timer with a given key:

    Timer::start('something');
Once you start the time with a given key, you can use the same key to refer to that particular timer. 
You can of course use PHP magic constants to make things easier:

    Timer::start(__FUNCTION__);
Attempting to start the timer with a non-scalar key will throw an `\InvalidArgumentException` exception.

####Read timer
After starting the timer, you can read the elapsed time at any time. Reading the time will not stop the timer. You can read the timer, do some expensive calculations, and read again to get the cumulative time. 

    Timer::read(); // Default timer. 
    Timer::read('default'); // Default timer. 
    Timer::read('something'); // Timer started with key "something".
Attempting to read a timer that is not started will throw an `\LogicException` exception. 

##### Formats
You can pass a second argument to let this library make minimal processing for you:

    Timer::read('something', Timer::FORMAT_PRECISE);
    // 0.10180473327637

See the formats section below for the formats supported.
#### Stop timer
You can stop the timer anytime as well. This makes the library store the stop time, and your further `Timer::read()` calls will always return the time it took between start and stop. 

    Timer::stop(); // Default timer. 
    Timer::stop('something'); // Timer started with key "something"

Attempting to stop a timer that is not started will throw an `\LogicException` exception. 

#### Reset timer
By default, starting the timer after stopping it will continue it from where it left off. For example, if you have 3 seconds on the timer when you stop it, and start it again, the total time will start from 3 seconds. You can explicitly reset the timer to make it start from 0. 
Resetting the timer will not make the timer start again. You need to explicitly start the timer again with a `Timer::start()` call. 

    Timer::reset(); // Default timer. 
    Timer::reset('something'); 
    Timer::resetAll(); // Resets all timers.

## Formats
Currently, the following formats are provided:

 - `FORMAT_PRECISE`: Precise timer value, without rounding it. e.g. `0.10180473327637`
 - `FORMAT_MILLISECONDS`:  Time in milliseconds, rounded to 2 decimals.
 - `FORMAT_SECONDS`: Time in seconds, rounded to 3 decimals. 

## Examples

#### Calculate the timer one-off:

    use Ayesh\PHP_Timer\Timer;
    
    Timer::start();
    // do your processing here.
    $time = Timer::read('default', Timer::FORMAT_SECONDS);
    echo "Script took {$time} second(s)";

#### Stop watch functionality, with stop-and-go timer calculated separately.

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

##Development and tests
All issues are PRs are welcome. Travis CI and PHPUnit tests are included. If you are adding new features, please make sure to add the test coverage.

##Credits
By [Ayesh Karunaratne](https://ayesh.me).



