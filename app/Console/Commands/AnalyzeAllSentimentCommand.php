<?php

namespace App\Console\Commands;

use App\Models\Ulasan;
use App\Services\SentimentAnalysisService;
use Illuminate\Console\Command;

class AnalyzeAllSentimentCommand extends Command
{
    protected $signature   = 'sentimen:analyze-all
                              {--force : Analisis ulang semua termasuk yang sudah ada}
                              {--limit=0 : Batasi jumlah ulasan yang diproses (0 = semua)}';
    protected $description = 'Analisis sentimen semua ulasan yang belum diproses';

    public function handle(SentimentAnalysisService $service): int
    {
        $force = $this->option('force');
        $limit = (int) $this->option('limit');

        $query = Ulasan::whereNotNull('teks_ulasan');

        if (! $force) {
            $query->whereDoesntHave('analisisSentimen');
        }

        if ($limit > 0) {
            $query->limit($limit);
        }

        $total = $query->count();

        if ($total === 0) {
            $this->info('Semua ulasan sudah dianalisis. Gunakan --force untuk analisis ulang.');
            return Command::SUCCESS;
        }

        $this->info("Menganalisis {$total} ulasan...");
        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $berhasil = 0;
        $gagal    = 0;

        $query->chunk(50, function ($ulasanChunk) use ($service, $bar, &$berhasil, &$gagal) {
            foreach ($ulasanChunk as $u) {
                try {
                    $service->analyzeAndSave($u->id, $u->teks_ulasan);
                    $berhasil++;
                } catch (\Throwable $e) {
                    $gagal++;
                }
                $bar->advance();
            }
        });

        $bar->finish();
        $this->newLine(2);
        $this->info("✅ Selesai: {$berhasil} berhasil, {$gagal} gagal.");

        return Command::SUCCESS;
    }
}
