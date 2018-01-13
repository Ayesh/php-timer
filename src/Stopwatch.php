<?php


namespace Ayesh\PHP_Timer;


class Stopwatch {
  private $accrued = 0;
  private $timestamp;
  private $running = false;

  public function __construct() {
    $this->start();
  }

  private function getTimestamp(): float {
    return microtime(true);
  }

  private function accrue(): void {
    $this->accrued = $this->read();
  }

  public function start(): void {
    if ($this->running) {
      return;
    }

    $this->accrue();
    $this->running = true;
    $this->timestamp = $this->getTimestamp();
  }

  public function read(): float {
    if ($this->running) {
      return $this->accrued + ($this->getTimestamp() - $this->timestamp);
    }
    return $this->accrued;
  }

  public function stop(): float {
    $this->accrue();
    $this->running = false;
    return $this->read();
  }
}
