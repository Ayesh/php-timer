<?php

namespace Ayesh\PHP_Timer\Tests;

use Ayesh\PHP_Timer\Stopwatch;
use PHPUnit\Framework\TestCase;

/**
 * @group time-sensitive
 */
class StopwatchTest extends TestCase {
  public function testTimerStoppedValueRetention() {
    $stopwatch = new Stopwatch();
    $stopwatch->stop();
    $time_1 = $stopwatch->read();
    $this->assertInternalType('float', $time_1);
    sleep(20);
    $time_2 = $stopwatch->read();
    $this->assertInternalType('float', $time_2);
    $this->assertSame($time_1, $time_2);
  }

  public function testTimerContiniuous() {
    $stopwatch = new Stopwatch();
    $time_1 = $stopwatch->read();
    $this->assertInternalType('float', $time_1);
    sleep(20);
    $time_2 = $stopwatch->read();
    $this->assertInternalType('float', $time_2);
    $this->assertNotSame($time_1, $time_2);
  }

  public function testTimerMultipleStartCalls() {
    $stopwatch = new Stopwatch();
    $stopwatch->start();
    $time_1 = $stopwatch->read();
    sleep(20);
    $time_2 = $stopwatch->read();
    $this->assertInternalType('float', $time_2);
    $this->assertNotSame($time_1, $time_2);
  }
}
