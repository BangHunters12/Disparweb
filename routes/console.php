<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Schedule;

Schedule::command('saw:recalculate')->dailyAt('02:00');
