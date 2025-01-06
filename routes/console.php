<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('debts:process-pending')
    ->everyThirtySeconds();
