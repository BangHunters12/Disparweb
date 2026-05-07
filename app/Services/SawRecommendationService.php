<?php

namespace App\Services;

use App\Models\AnalisisSentimen;
use App\Models\RekomendasiSaw;
use App\Models\Restoran;
use App\Models\SawConfig;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SawRecommendationService
{
    protected array $weights;
    protected array $criteriaTypes;

    public function __construct()
    {
        $config              = SawConfig::current();
        $this->weights       = $config->normalizedWeights();
        $this->criteriaTypes = config('saw.criteria_types');
    }

    public function calculateAll(): int
    {
        $restoran = Restoran::aktif()->get();
        if ($restoran->isEmpty()) return 0;

        $matrix     = $this->buildMatrix($restoran);
        $normalized = $this->normalizeMatrix($matrix);
        $results    = $this->weightedSum($normalized, $restoran);

        $this->saveResults($results);
        return count($results);
    }

    protected function buildMatrix($list): array
    {
        $matrix = [];
        foreach ($list as $r) {
            $pctPositif = 0;
            $total = AnalisisSentimen::whereHas('ulasan', fn($q) => $q->where('restoran_id', $r->id))->count();
            if ($total > 0) {
                $positif    = AnalisisSentimen::where('label_sentimen', 'positif')
                    ->whereHas('ulasan', fn($q) => $q->where('restoran_id', $r->id))->count();
                $pctPositif = $positif / $total;
            }

            $avgHarga = $this->avgHarga($r);

            $lastUlasan      = $r->ulasan()->latest()->first();
            $daysSinceLast   = Carbon::now()->diffInDays($lastUlasan?->created_at ?? $r->updated_at);
            $kebaruan        = max(1, config('saw.recency_days', 365) - $daysSinceLast);

            $matrix[$r->id] = [
                'rating'      => (float) $r->avg_rating ?: (float) config('saw.default_rating', 3.0),
                'sentimen'    => $pctPositif,
                'harga'       => $avgHarga,
                'popularitas' => $r->total_ulasan + ($r->total_views / 10),
                'kebaruan'    => $kebaruan,
            ];
        }
        return $matrix;
    }

    protected function normalizeMatrix(array $matrix): array
    {
        $criteria   = array_keys($this->criteriaTypes);
        $normalized = [];

        foreach ($criteria as $c) {
            $values = array_column($matrix, $c);
            $max    = max($values) ?: 1;
            $min    = min($values) ?: 1;

            foreach ($matrix as $id => $row) {
                $type = $this->criteriaTypes[$c] ?? 'benefit';
                $normalized[$id][$c] = $type === 'benefit'
                    ? ($row[$c] / $max)
                    : ($max > 0 && $row[$c] > 0 ? $min / $row[$c] : 0);
            }
        }
        return $normalized;
    }

    protected function weightedSum(array $normalized, $list): array
    {
        $results = [];
        foreach ($list as $r) {
            if (! isset($normalized[$r->id])) continue;
            $row = $normalized[$r->id];
            $final = ($row['rating'] * $this->weights['rating'])
                   + ($row['sentimen'] * $this->weights['sentimen'])
                   + ($row['harga'] * $this->weights['harga'])
                   + ($row['popularitas'] * $this->weights['popularitas'])
                   + ($row['kebaruan'] * $this->weights['kebaruan']);

            $results[] = [
                'restoran_id'      => $r->id,
                'skor_rating'      => round($row['rating'] * $this->weights['rating'], 4),
                'skor_sentimen'    => round($row['sentimen'] * $this->weights['sentimen'], 4),
                'skor_harga'       => round($row['harga'] * $this->weights['harga'], 4),
                'skor_popularitas' => round($row['popularitas'] * $this->weights['popularitas'], 4),
                'skor_kebaruan'    => round($row['kebaruan'] * $this->weights['kebaruan'], 4),
                'skor_saw_final'   => round($final, 4),
            ];
        }
        usort($results, fn($a, $b) => $b['skor_saw_final'] <=> $a['skor_saw_final']);
        foreach ($results as $i => &$r) { $r['peringkat'] = $i + 1; }
        return $results;
    }

    protected function saveResults(array $results): void
    {
        DB::transaction(function () use ($results) {
            foreach ($results as $data) {
                RekomendasiSaw::updateOrCreate(
                    ['restoran_id' => $data['restoran_id']],
                    array_merge($data, ['dihitung_at' => now()])
                );
            }
        });
    }

    protected function avgHarga(Restoran $r): float
    {
        $prices = array_filter([(float)$r->harga_min, (float)$r->harga_max], fn($p) => $p > 0);
        return empty($prices) ? (float) config('saw.default_price', 50000) : array_sum($prices) / count($prices);
    }

    public function getScoredList()
    {
        return RekomendasiSaw::with(['restoran.kecamatan'])->orderBy('peringkat')->get();
    }
}
