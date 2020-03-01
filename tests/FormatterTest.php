<?php

namespace Ayesh\PHP_Timer\Tests;

use Ayesh\PHP_Timer\Formatter;
use PHPUnit\Framework\TestCase;

class FormatterTest extends TestCase {
  public function testClassInit(): void {
    $this->expectException(\Error::class);
    new Formatter();
  }

  public function testSecondFormatter(): void {
    $count = Formatter::formatTime(30000);
    $this->assertSame('30 sec', $count);
  }

  public function testSecondMinuteFormatter(): void {
    $count = Formatter::formatTime(65000);
    $this->assertSame('1 min 5 sec', $count);
  }

  public function testDaySecondFormatting(): void {
    $count = Formatter::formatTime(86400000 + 5000);
    $this->assertSame('1 day 5 sec', $count);
  }
}
