<?php
declare(strict_types=1);

namespace Ayesh\PHP_Timer;

/**
 * Class Timer
 *
 * Helper class to measure the execusion time between two points in a single
 * request.
 *
 * @package Ayesh\PHP_Timer
 */
class Timer {
  const FORMAT_PRECISE = FALSE;
  const FORMAT_MILLISECONDS = 'ms';
  const FORMAT_SECONDS = 's';
  const FORMAT_HUMAN = 'h';

  /**
   * Stores all the timers statically.
   * @var array
   */
  static private $timers = [];

  /**
   * Returns the current time as a float.
   * @return float
   */
  private static function getCurrentTime(): float {
    return microtime(true);
  }

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
  public static function start(string $key = 'default') {
    if (isset(self::$timers[$key])) {
      if (empty(self::$timers[$key][0])) {
        self::$timers[$key][0] = true;
        self::$timers[$key][1] = static::getCurrentTime();
      }
    }
    else {
      self::$timers[$key] = [
        true,
        static::getCurrentTime(),
        0
      ];
    }
  }

  /**
   * Resets a specific timer, or default timer if not passed the $key parameter.
   * To reset all timers, call @see \Ayesh\PHP_Timer\Timer::resetAll().
   * @param string $key
   */
  public static function reset(string $key = 'default') {
    unset(self::$timers[$key]);
  }

  /**
   * Resets ALL timers.
   * To reset a specific timer, @see \Ayesh\PHP_Timer\Timer::reset().
   */
  public static function resetAll() {
    self::$timers = [];
  }

  /**
   * Pocesses the internal timer state to return the time elapsed.
   * @param $value
   * @param $format
   * @return mixed
   */
  protected static function processTimerValue($value, $format) {
    if ($value[0]) {
      return static::formatTime((static::getCurrentTime() - $value[1]) + $value[2], $format);
    }
    return static::formatTime($value[2], $format);
  }

  /**
   * Formats the given time the processor into the given format.
   * @param $value
   * @param $format
   * @return float
   */
  private static function formatTime($value, $format) {
    switch ($format) {

      case static::FORMAT_PRECISE;
        return $value * 1000;

      case static::FORMAT_MILLISECONDS:
        return round($value * 1000, 2);

      case static::FORMAT_SECONDS:
        return round($value, 3);

      default:
        return $value * 1000;
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
      return static::processTimerValue(self::$timers[$key], $format);
    }
    throw new \LogicException('Reading timer when the given key timer was not initialized.');
  }

  /**
   * Stops the timer with the given key. Default key is "default"
   * @param string $key
   */
  public static function stop($key = 'default') {
    if (isset(self::$timers[$key])) {
      $ct = static::getCurrentTime();
      self::$timers[$key][0] = false;
      self::$timers[$key][2] += $ct - self::$timers[$key][1];
    }
    else {
      throw new \LogicException('Stopping timer when the given key timer was not initialized.');
    }
  }
}
