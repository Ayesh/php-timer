<?php

namespace Ayesh\PHP_Timer\Tests;

use Ayesh\PHP_Timer\Stopwatch;
use PHPUnit\Framework\TestCase;

/**
 * @group time-sensitive
 */
class StopwatchTest extends TestCase {

  public function testTimerStoppedValueRetention(): void {
    $stopwatch = new Stopwatch();
    $stopwatch->stop();
    $time_1 = $stopwatch->read();
    $this->assertIsFloat($time_1);
    sleep(20);
    $time_2 = $stopwatch->read();
    $this->assertIsFloat($time_2);
    $this->assertSame($time_1, $time_2);
  }

  public function testTimerContiniuous(): void {
    $stopwatch = new Stopwatch();
    $time_1    = $stopwatch->read();
    $this->assertIsFloat($time_1);
    sleep(20);
    $time_2 = $stopwatch->read();
    $this->assertIsFloat($time_2);
    $this->assertNotSame($time_1, $time_2);
  }

  public function testTimerMultipleStartCalls(): void {
    $stopwatch = new Stopwatch();
    $stopwatch->start();
    $time_1 = $stopwatch->read();
    sleep(20);
    $time_2 = $stopwatch->read();
    $this->assertIsFloat($time_2);
    $this->assertNotSame($time_1, $time_2);
  }
}
