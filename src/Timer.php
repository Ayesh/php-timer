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
  private static $timers = [];

  /**
   * Returns the current time as a float.
   * @return float
   */
  static private function getCurrentTime(): float {
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
  static public function start(string $key = 'default') {
    if (isset(static::$timers[$key])) {
      if (empty(static::$timers[$key][0])) {
        static::$timers[$key][0] = true;
        static::$timers[$key][1] = static::getCurrentTime();
      }
    }
    else {
      static::$timers[$key] = [
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
  static public function reset(string $key = 'default') {
    unset(static::$timers[$key]);
  }

  /**
   * Resets ALL timers.
   * To reset a specific timer, @see \Ayesh\PHP_Timer\Timer::reset().
   */
  static public function resetAll() {
    static::$timers = [];
  }

  /**
   * Pocesses the internal timer state to return the time elapsed.
   * @param $value
   * @param $format
   * @return mixed
   */
  final static protected function processTimerValue($value, $format) {
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
  static protected function formatTime($value, $format) {
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
  static public function read(string $key = 'default', $format = self::FORMAT_MILLISECONDS) {
    if (isset(static::$timers[$key])) {
      return static::processTimerValue(static::$timers[$key], $format);
    }
    throw new \LogicException('Reading timer when the given key timer was not initialized.');
  }

  /**
   * Stops the timer with the given key. Default key is "default"
   * @param string $key
   */
  static public function stop($key = 'default') {
    if (isset(static::$timers[$key])) {
      $ct = static::getCurrentTime();
      static::$timers[$key][0] = false;
      static::$timers[$key][2] += $ct - static::$timers[$key][1];
    }
    else {
      throw new \LogicException('Stopping timer when the given key timer was not initialized.');
    }
  }
}
