<?php


namespace Ayesh\PHP_Timer;

/**
 * Class Formatter
 * Formatter helper to format time intervals.
 * @internal
 * @package Ayesh\PHP_Timer
 */
class Formatter {

  private function __construct() {
  }

  public static function formatTime(int $milliseconds): string {
    $units = [ // Do not reorder the array order.
      31536000000 => ['1 year', '@count years'],
      2592000000 => ['1 month', '@count months'],
      604800000 => ['1 week', '@count weeks'],
      86400000 => ['1 day', '@count days'],
      3600000 => ['1 hour', '@count hours'],
      60000 => ['1 min', '@count min'],
      1000 => ['1 sec', '@count sec'],
      1 => ['1 ms', '@count ms'],
    ];

    $granularity = 2;
    $output = [];
    foreach ($units as $value => $string_pair) {
      if ($milliseconds >= $value) {
        $output[]    = static::formatPlural((int) floor($milliseconds / $value), $string_pair[0], $string_pair[1]);
        $milliseconds %= $value;
        $granularity--;
      }
      if ($granularity === 0) {
        break;
      }
    }

    return $output ? implode(' ', $output) : '0 sec';
  }

  protected static function formatPlural(int $count, string $singular, string $plural, array $args = array()): string {
    $args['@count'] = $count;
    return $count === 1
      ? strtr($singular, $args)
      : strtr($plural, $args);
  }
}
