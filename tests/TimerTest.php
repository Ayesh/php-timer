<?php

namespace Ayesh\PHP_Timer\Tests;

use Ayesh\PHP_Timer\Timer;
use PHPUnit\Framework\TestCase;

/**
 * @package Ayesh\PHP_Timer\Tests
 * @group time-sensitive
 * @deprecated
 */
class TimerTest extends TestCase {

  public function testUnsupportedKeyType(){
    $this->expectException(\TypeError::class);
    Timer::start(new \stdClass());
  }

  private function sleepHalfSec(int $count = 1) {
    usleep(500000 * $count);
  }

  public function testStopValuesRetained() {
    Timer::start(__FUNCTION__);
    Timer::stop(__FUNCTION__);
    $stopped_at = Timer::read(__FUNCTION__, Timer::FORMAT_PRECISE);
    $this->sleepHalfSec();
    $this->assertEquals(Timer::read(__FUNCTION__, Timer::FORMAT_PRECISE), $stopped_at);
  }

  public function testUnstoppedValuesContinue() {
    Timer::start(__FUNCTION__);
    $stopped_at = Timer::read(__FUNCTION__);
    usleep(500);
    $this->assertGreaterThan($stopped_at, Timer::read(__FUNCTION__, Timer::FORMAT_PRECISE));
  }

  public function testIndividualTimersRunConcurrent() {
    Timer::resetAll();

    Timer::start(1);
    Timer::start(2);
    Timer::start(3);

    Timer::stop(1);
    $timer_1 = Timer::read(1, Timer::FORMAT_PRECISE);

    usleep(500);

    $this->assertNotEquals(Timer::read(2, Timer::FORMAT_PRECISE), $timer_1);
    $this->assertNotEquals(Timer::read(3, Timer::FORMAT_PRECISE), $timer_1);
    $this->assertEquals(Timer::read(1, Timer::FORMAT_PRECISE), $timer_1);
  }

  public function testMultipleStartsQueued() {
    $key = __FUNCTION__;
    Timer::start($key);
    $this->sleepHalfSec();
    $timer_1 = Timer::read($key, Timer::FORMAT_PRECISE);
    $this->assertGreaterThanOrEqual(450, $timer_1);

    Timer::start($key);
    $this->sleepHalfSec();
    $timer_2 = Timer::read($key, Timer::FORMAT_PRECISE);
    $this->assertGreaterThanOrEqual(900, $timer_2);

    Timer::start($key);
    $this->sleepHalfSec(2);
    $timer_3 = Timer::read($key, Timer::FORMAT_PRECISE);
    $this->assertGreaterThanOrEqual(1900, $timer_3);

    $this->assertGreaterThan($timer_1, $timer_2);
    $this->assertGreaterThan($timer_2, $timer_3);
    $this->assertGreaterThan($timer_1 + $timer_2, $timer_3);
  }

  public function testMultipleStartCallsQueued_2() {
    $key = 'foo';
    Timer::start($key);
    $this->assertLessThan(500, Timer::read($key));
    $this->sleepHalfSec(2);
    $this->assertGreaterThanOrEqual(1000, Timer::read($key));
    Timer::start($key);
    $this->sleepHalfSec();
    $this->assertGreaterThanOrEqual(1500, Timer::read($key));
    $this->assertLessThan(2000, Timer::read($key));
  }

  public function testStopAndGoTimer() {
    Timer::start(__FUNCTION__);
    usleep(1000);
    Timer::stop(__FUNCTION__);
    usleep(5000);
    Timer::start(__FUNCTION__);
    usleep(2000);
    $timer = Timer::read(__FUNCTION__, Timer::FORMAT_SECONDS);

    $this->assertGreaterThanOrEqual(0.003, $timer);
    $this->assertLessThan(0.008, $timer);
  }

  public function testResetRestsTimer() {
    Timer::resetAll();
    Timer::start(__FUNCTION__);
    Timer::reset(__FUNCTION__);
    $this->expectException(\LogicException::class);
    Timer::read(__FUNCTION__);
  }

  public function testTimerFormat_Seconds() {
    Timer::start(__FUNCTION__);

    usleep(1000);
    $read = Timer::read(__FUNCTION__, Timer::FORMAT_SECONDS);
    $this->assertGreaterThanOrEqual('0.001', $read);

    usleep(1000);
    $read = Timer::read(__FUNCTION__, Timer::FORMAT_SECONDS);
    $this->assertGreaterThanOrEqual('0.002', $read);
  }

  public function testTimerFormat_Human() {
    Timer::start(__FUNCTION__);

    usleep(1000);
    $read = Timer::read(__FUNCTION__, Timer::FORMAT_HUMAN);
    $this->assertSame('1 ms', $read);

    sleep(1);
    $read = Timer::read(__FUNCTION__, Timer::FORMAT_HUMAN);
    $this->assertSame('1 second', $read);
  }

  public function testTimerFormat_Unspecified() {
    Timer::start(__FUNCTION__);
    usleep(1500);
    $read = Timer::read(__FUNCTION__, 'unspecified');

    $this->assertGreaterThanOrEqual(1, $read);
  }

  public function testTimerFormat_Milliseconds() {
    Timer::start(__FUNCTION__);
    usleep(1500);
    $read = Timer::read(__FUNCTION__, Timer::FORMAT_MILLISECONDS);

    $this->assertGreaterThanOrEqual(1, $read);
  }

  public function testStopWithoutInitializing() {
    Timer::resetAll();
    $this->expectException(\LogicException::class);
    Timer::stop(__FUNCTION__);
  }


  public function testValidSecondsCount() {
    Timer::start(__FUNCTION__);
    $this->assertIsString(Timer::read(__FUNCTION__));
  }

  public function testDenyAccessWithoutInitializing() {
    $this->expectException(\LogicException::class);
    Timer::resetAll();
    Timer::read();
  }


}
