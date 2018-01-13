<?php
declare(strict_types = 1);

namespace Ayesh\PHP_Timer;

/**
 * Class Timer
 *
 * Helper class to measure the execution time between two points in a single
 * request.
 *
 * @package Ayesh\PHP_Timer
 */
class Timer {
  public const FORMAT_PRECISE = FALSE;
  public const FORMAT_MILLISECONDS = 'ms';
  public const FORMAT_SECONDS = 's';
  public const FORMAT_HUMAN = 'h';

  /**
   * Stores all the timers statically.
   * @var Stopwatch[]
   */
  static private $timers = [];

  /**
   * Start or resume the timer.
   *
   * Call this method to start the timer with a given key. The default key
   * is "default", and used in @see \Ayesh\PHP_Timer\Timer::read() and reset()
   * methods as well
   *
   * Calling this with the same $key will not restart the timer if it has already
   * started.
   *
   * @param string $key
   */
  public static function start(string $key = 'default'): void {
    if (isset(self::$timers[$key])) {
      self::$timers[$key]->start();
    }
    else {
      self::$timers[$key] = new Stopwatch();
    }
  }

  /**
   * Resets a specific timer, or default timer if not passed the $key parameter.
   * To reset all timers, call @see \Ayesh\PHP_Timer\Timer::resetAll().
   * @param string $key
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
   * Formats the given time the processor into the given format.
   * @param $value
   * @param $format
   * @return float
   */
  private static function formatTime($value, $format): string {
    switch ($format) {

      case static::FORMAT_PRECISE:
        return (string) ($value * 1000);

      case static::FORMAT_MILLISECONDS:
        return (string) round($value * 1000, 2);

      case static::FORMAT_SECONDS:
        return (string) round($value, 3);

      default:
        return (string) ($value * 1000);
    }
  }

  /**
   * Returns the time elapsed in the format requested in the $format parameter.
   * To access a specific timer, pass the same key that
   * @see \Ayesh\PHP_Timer\Timer::start() was called with. If the timer was not
   * started, a \LogicException will be thrown.
   *
   * The default format is milliseconds. See the class constants for additional
   * formats.
   *
   * @param string $key The key that the timer was started with. Default value is
   *   "default" throughout the class.
   * @param string $format
   * @return mixed The formatted time.
   * @throws \LogicException
   */
  public static function read(string $key = 'default', $format = self::FORMAT_MILLISECONDS) {
    if (isset(self::$timers[$key])) {
      return self::formatTime(self::$timers[$key]->read(), $format);
    }
    throw new \LogicException('Reading timer when the given key timer was not initialized.');
  }

  /**
   * Stops the timer with the given key. Default key is "default"
   *
   * @param string $key
   *
   * @throws \LogicException If the attempted timer has not started already.
   */
  public static function stop($key = 'default'): void {
    if (isset(self::$timers[$key])) {
      self::$timers[$key]->stop();
    } else {
      throw new \LogicException('Stopping timer when the given key timer was not initialized.');
    }
  }
}
