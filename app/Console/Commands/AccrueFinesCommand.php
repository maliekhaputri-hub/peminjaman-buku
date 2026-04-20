<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;

class AccrueFinesCommand extends Command
{
    protected $signature = 'fines:accrue';
    protected $description = 'Accrue daily fines for overdue transactions';

    public function handle()
    {
        $updated = Transaction::accrueDailyFines();
        $this->info("Updated fines for {$updated} overdue transactions");
    }
}

