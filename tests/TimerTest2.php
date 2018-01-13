<?php

namespace Ayesh\PHP_Timer\Tests;

use Ayesh\PHP_Timer\Timer;
use PHPUnit\Framework\TestCase;

/**
 * @package Ayesh\PHP_Timer\Tests
 * @group time-sensitive
 */
class TimerTest2 extends TestCase {
  public function testTimerDefaultRunning() {
    Timer::start();
    sleep(20);
    $this->assertSame('20000', Timer::read());
  }

  public function testNamedTimerRunning() {
    $name = __FUNCTION__;
    Timer::start($name);
    $time_1 = Timer::read($name);
    $time_2 = Timer::read($name);
    $this->assertSame($time_1, $time_2);
  }

  public function testUnknownTimerThrowsException() {
    Timer::start(__FUNCTION__);
    Timer::reset(__FUNCTION__);
    $this->expectException(\LogicException::class);
    Timer::read(__FUNCTION__);
  }

  public function testMultipleStartsContinueTimer() {
    Timer::start(__FUNCTION__);
    sleep(20);
    Timer::start(__FUNCTION__);
    sleep(20);
    $this->assertSame('40000', Timer::read(__FUNCTION__));
  }

  public function testResetAllUnset() {
    Timer::start(__FUNCTION__);
    sleep(20);
    Timer::resetAll();
    $this->expectException(\LogicException::class);
    Timer::read(__FUNCTION__);
  }

  public function testResetAllReRun() {
    Timer::start(__FUNCTION__);
    sleep(20);
    Timer::resetAll();
    Timer::start(__FUNCTION__);
    sleep(20);
    $this->assertSame('20000', Timer::read(__FUNCTION__));
  }

  public function testStopUnknownTimer() {
    $this->expectException(\LogicException::class);
    Timer::stop(__FUNCTION__);
  }

  public function testStopKnownTimer() {
    Timer::start(__FUNCTION__);
    sleep(20);
    $this->assertSame('20000', Timer::read(__FUNCTION__));
    Timer::stop(__FUNCTION__);
    $this->assertSame('20000', Timer::read(__FUNCTION__));
    sleep(30);
    $this->assertSame('20000', Timer::read(__FUNCTION__));
    Timer::start(__FUNCTION__);
    sleep(23);
    $this->assertSame('43000', Timer::read(__FUNCTION__));
  }
}
