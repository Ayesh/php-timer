<?php
declare(strict_types=1);

namespace Ayesh\PHP_Timer;

use LogicException;
use function round;

/**
 * Class Timer
 *
 * Helper class to measure the execution time between two points in a single
 * request.
 *
 * @package Ayesh\PHP_Timer
 */
class Timer {

  public const FORMAT_PRECISE = false;

  public const FORMAT_MILLISECONDS = 'ms';

  public const FORMAT_SECONDS = 's';

  public const FORMAT_HUMAN = 'h';

  private const TIMES = [
    'hour'   => 3600000,
    'minute' => 60000,
    'second' => 1000,
  ];

  /**
   * Stores all the timers statically.
   *
   * @var Stopwatch[]
   */
  static private $timers = [];

  /**
   * Start or resume the timer.
   *
   * Call this method to start the timer with a given key. The default key
   * is "default", and used in @param string $key
   *
   * @see \Ayesh\PHP_Timer\Timer::read() and reset()
   * methods as well
   *
   * Calling this with the same $key will not restart the timer if it has already
   * started.
   *
   */
  public static function start(string $key = 'default'): void {
    if (isset(self::$timers[$key])) {
      self::$timers[$key]->start();
      return;
    }

    self::$timers[$key] = new Stopwatch();
  }

  /**
   * Resets a specific timer, or default timer if not passed the $key parameter.
   * To reset all timers, call @param string $key
   *
   * @see \Ayesh\PHP_Timer\Timer::resetAll().
   */
  public static function reset(string $key = 'default'): void {
    unset(self::$timers[$key]);
  }

  /**
   * Resets ALL timers.
   * To reset a specific timer, @see \Ayesh\PHP_Timer\Timer::reset().
   */
  public static function resetAll(): void {
    self::$timers = [];
  }

  /**
   * Returns the time elapsed in the format requested in the $format parameter.
   * To access a specific timer, pass the same key that
   *
   * @param string $key The key that the timer was started with. Default value is
   *   "default" throughout the class.
   * @param string $format The default format is milliseconds. See the class constants for additional
   *   formats.
   *
   * @return mixed The formatted time, formatted by the formatter string passed for $format.
   * @throws LogicException
   * If the timer was not started, a \LogicException will be thrown. Use @see \Ayesh\PHP_Timer\Timer::start()
   * to start a timer.
   */
  public static function read(string $key = 'default', $format = self::FORMAT_MILLISECONDS) {
    if (isset(self::$timers[$key])) {
      return self::formatTime(self::$timers[$key]->read(), $format);
    }

    throw new LogicException('Reading timer when the given key timer was not initialized.');
  }

  /**
   * Formats the given time the processor into the given format.
   *
   * @param float $value
   * @param string|bool $format
   *
   * @return string
   */
  private static function formatTime(float $value, $format): string {
    switch ($format) {
      case static::FORMAT_MILLISECONDS:
        return (string) round($value * 1000, 2);

      case static::FORMAT_SECONDS:
        return (string) round($value, 3);

      case static::FORMAT_HUMAN:
        return static::secondsToTimeString($value);

      case static::FORMAT_PRECISE:
      default:
        return (string) ($value * 1000);
    }
  }

  private static function secondsToTimeString(float $time): string {
    $ms = (int) round($time * 1000);
    return Formatter::formatTime($ms);
  }

  /**
   * Stops the timer with the given key. Default key is "default"
   *
   * @param string $key
   *
   * @throws LogicException If the attempted timer has not started already.
   */
  public static function stop($key = 'default'): void {
    if (!isset(self::$timers[$key])) {
      throw new LogicException('Stopping timer when the given key timer was not initialized.');
    }

    self::$timers[$key]->stop();
  }

  /**
   * Return a list of timer names. Note that resetting a timer removes the timer.
   *
   * @return string[]
   */
  public static function getTimers(): array {
    return array_keys(self::$timers);
  }
}
