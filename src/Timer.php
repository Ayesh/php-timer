<?php

namespace Ayesh\PHP_Timer;

class Timer {
  const FORMAT_PRECISE = FALSE;
  const FORMAT_MILLISECONDS = 'ms';
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

  static public function reset($key = 'default') {
    unset(static::$timers[$key]);
  }

  static public function resetAll() {
    static::$timers = [];
  }

  final static protected function processTimerValue($value, $format) {
    if ($value[0]) {
      return static::formatTime((static::getCurrentTime() - $value[1]) + $value[2], $format);
    }
    else {
      return static::formatTime($value[2], $format);
    }
  }

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

  static public function read($key = 'default', $format = self::FORMAT_MILLISECONDS) {
    if (isset(static::$timers[$key])) {
      return static::processTimerValue(static::$timers[$key], $format);
    }
    throw new \LogicException('Reading timer when the given key timer was not initialized.');
  }

  static public function stop($key = 'default') {
    if (isset(static::$timers[$key])) {
      $ct = static::getCurrentTime();
      static::$timers[$key][0] = false;
      static::$timers[$key][2] = static::$timers[$key][2] + ($ct - static::$timers[$key][1]);
    }
    else {
      throw new \LogicException('Stopping timer when the given key timer was not initialized.');
    }
  }
}
