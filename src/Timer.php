<?php

namespace Ayesh\PHP_Timer;

class Timer {
  const FORMAT_MILISECONDS = 1;
  const FORMAT_SECONDS = 1000;

  static $timers = [];


  static private function getCurrentTime() {
    return microtime(true);
  }

  static public function start($key = 'default') {
    if (!is_scalar($key)) {
      throw new \InvalidArgumentException('Key should be a scalar value.');
    }
    else {
      static::$timers[$key] = static::getCurrentTime();
    }
  }

  final static protected function processTimerValue($value, $format) {
    if (is_array($value)) {
      return static::formatTime($value[1] - $value[0], $format);
    }
    return static::formatTime(static::getCurrentTime() - $value, $format);
  }

  static protected function formatTime($value, $format) {
    if (!defined('static::'. $format)) {
      throw new \InvalidArgumentException('Unknown format.');
    }

    switch ($format) {
      default:
        return $value / $format;
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

  static public function stop($key) {
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