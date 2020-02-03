<?php

namespace Ayesh\PHP_Timer\Tests;

use Ayesh\PHP_Timer\Timer;
use PHPUnit\Framework\TestCase;

/**
 * @package Ayesh\PHP_Timer\Tests
 * @group time-sensitive
 */
class TimerFormatterTest extends TestCase {
  public function testTimerFormat_Human3(): void {
    Timer::start(__FUNCTION__);

    sleep(1);
    $read = Timer::read(__FUNCTION__, Timer::FORMAT_HUMAN);
    $this->assertSame('1 sec', $read);

    sleep(1);
    $read = Timer::read(__FUNCTION__, Timer::FORMAT_HUMAN);
    $this->assertSame('2 sec', $read);

    sleep(60);
    $read = Timer::read(__FUNCTION__, Timer::FORMAT_HUMAN);
    $this->assertSame('1 min 2 sec', $read);

    sleep(86400);
    $read = Timer::read(__FUNCTION__, Timer::FORMAT_HUMAN);
    $this->assertSame('1 day 1 min', $read);
  }
}
