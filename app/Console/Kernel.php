<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\AccrueFinesCommand::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('fines:accrue')
                 ->daily()
                 ->at('00:01');
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

