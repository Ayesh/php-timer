<?php

use Ayesh\PHP_Timer\Timer;

require "src/Timer.php";

echo '<br />';

Timer::start('full');

Timer::start('laps');
sleep(1);
Timer::stop('laps');

sleep(2);

Timer::start('laps');
sleep(1);
Timer::stop('laps');

echo Timer::read('full', Timer::FORMAT_SECONDS);
echo "<br />";
echo Timer::read('laps', Timer::FORMAT_SECONDS);
