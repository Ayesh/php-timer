<?php

namespace Ayesh\PHP_Timer\Tests;

use Ayesh\PHP_Timer\Timer;
use PHPUnit\Framework\TestCase;

/**
 * @package Ayesh\PHP_Timer\Tests
 * @group time-sensitive
 */
class TimerFormatterTest extends TestCase {
  public function testTimerFormat_Human(): void {
    Timer::start(__FUNCTION__);

    usleep(1000);
    $read = Timer::read(__FUNCTION__, Timer::FORMAT_HUMAN);
    $this->assertSame('1 ms', $read);

    sleep(1);
    $read = Timer::read(__FUNCTION__, Timer::FORMAT_HUMAN);
    $this->assertSame('1 second', $read);
  }
}
