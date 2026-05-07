<?php

namespace App\Console\Commands;

use App\Jobs\RecalculateSawJob;
use Illuminate\Console\Command;

class RecalculateSawCommand extends Command
{
    protected $signature   = 'saw:recalculate';
    protected $description = 'Menghitung ulang skor SAW untuk semua restoran aktif';

    public function handle(): int
    {
        $this->info('Dispatching SAW recalculation job...');
        RecalculateSawJob::dispatch();
        $this->info('Job berhasil di-dispatch ke queue saw.');
        return Command::SUCCESS;
    }
}
