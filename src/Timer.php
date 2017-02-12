<?php

namespace Ayesh\PHP_Timer;

class Timer {
  const FORMAT_PRECISE = FALSE;
  const FORMAT_MILISECONDS = 'ms';
  const FORMAT_SECONDS = 's';

  private static $timers = [];

  static private function getCurrentTime() {
    return microtime(true);
  }

  static public function start($key = 'default') {
    if (!is_scalar($key)) {
      throw new \InvalidArgumentException('Key should be a scalar value.');
    }
    elseif (isset(static::$timers[$key])) {
      if (is_array(static::$timers[$key])) {
        static::$timers[$key] = static::$timers[$key][0];
      }
    }
    else {
      static::$timers[$key] = static::getCurrentTime();
    }
  }

  static public function reset($key = 'default') {
    unset(static::$timers[$key]);
  }

  static public function resetAll() {
    static::$timers = [];
  }

  final static protected function processTimerValue($value, $format) {
    if (is_array($value)) {
      return static::formatTime($value[1] - $value[0], $format);
    }
    return static::formatTime(static::getCurrentTime() - $value, $format);
  }

  static protected function formatTime($value, $format) {
    switch ($format) {

      case static::FORMAT_PRECISE;
        return $value * 1000;

      case static::FORMAT_MILISECONDS:
        return round($value * 1000, 2);

      case static::FORMAT_SECONDS:
        return round($value, 3);

      default:
        return $value * 1000;
    }
  }

  static public function read($key = 'default', $format = self::FORMAT_MILISECONDS) {
    if (isset(static::$timers[$key])) {
      return static::processTimerValue(static::$timers[$key], $format);
    }
    else {
      throw new \LogicException('Reading timer when the given key timer was not initialized.');
    }
  }

  static public function stop($key = 'default') {
    if (isset(static::$timers[$key])) {
      if (!is_array(static::$timers[$key])) {
        static::$timers[$key] = [
          static::$timers[$key],
          static::getCurrentTime(),
        ];
      }
      else {
        static::$timers[$key][1] = static::getCurrentTime();
      }
    }
    else {
      throw new \LogicException('Stoping timer when the given key timer was not initialized.');
    }
  }
}
