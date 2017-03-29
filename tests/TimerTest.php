<?php

namespace Ayesh\PHP_Timer\Test;

use Ayesh\PHP_Timer\Timer;
use PHPUnit\Framework\TestCase;

class TimerTest extends TestCase {

  public function testUnsupportedKeyType(){
    $this->expectException(\TypeError::class);
    Timer::start(new \stdClass());
  }

  public function testValidBareStart() {
    Timer::resetAll();
    Timer::start();
    $return = Timer::read('default', Timer::FORMAT_PRECISE);
    $this->assertTrue($return > 0);
  }

  public function testStopValuesRetained() {
    Timer::start(__FUNCTION__);
    Timer::stop(__FUNCTION__);
    $stopped_at = Timer::read(__FUNCTION__, Timer::FORMAT_PRECISE);
    usleep(500);
    $this->assertEquals(Timer::read(__FUNCTION__, Timer::FORMAT_PRECISE), $stopped_at);
  }

  public function testUnstoppedValuesContinue() {
    Timer::start(__FUNCTION__);
    $stopped_at = Timer::read(__FUNCTION__);
    usleep(500);
    $this->assertNotEquals(Timer::read(__FUNCTION__, Timer::FORMAT_PRECISE), $stopped_at);
  }

  public function testIndividualTimersRunConcurrent() {
    timer::resetAll();

    timer::start(1);
    timer::start(2);
    timer::start(3);

    Timer::stop(1);
    $timer_1 = Timer::read(1, Timer::FORMAT_PRECISE);

    usleep(500);

    $this->assertNotEquals(Timer::read(2, Timer::FORMAT_PRECISE), $timer_1);
    $this->assertNotEquals(Timer::read(3, Timer::FORMAT_PRECISE), $timer_1);
    $this->assertEquals(Timer::read(1, Timer::FORMAT_PRECISE), $timer_1);
  }

  public function testMultipleStartsQueued() {
    Timer::start(__FUNCTION__);
    usleep(500);
    $timer_1 = Timer::read(__FUNCTION__, Timer::FORMAT_PRECISE);

    Timer::start(__FUNCTION__);
    usleep(500);
    $timer_2 = Timer::read(__FUNCTION__, Timer::FORMAT_PRECISE);

    Timer::start(__FUNCTION__);
    usleep(500);
    $timer_3 = Timer::read(__FUNCTION__, Timer::FORMAT_PRECISE);

    $this->assertTrue($timer_2 > $timer_1);
    $this->assertTrue($timer_3 > $timer_2);
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
    $this->assertInternalType('double', Timer::read(__FUNCTION__));
  }

  public function testDenyAccessWithoutInitializing() {
    $this->expectException(\LogicException::class);
    Timer::resetAll();
    Timer::read();
  }


}
