<?php

namespace App\Services;

use App\Models\Tempat;
use App\Models\RekomendasiSaw;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SawRecommendationService
{
    protected array $weights;
    protected array $criteriaTypes;

    public function __construct()
    {
        $this->weights = config('saw.weights');
        $this->criteriaTypes = config('saw.criteria_types');
    }

    /**
     * Recalculate SAW scores for all active tempat
     */
    public function recalculateAll(?string $userId = null): int
    {
        $minReviews = config('saw.min_reviews', 1);

        $tempatList = Tempat::aktif()
            ->withCount('ulasan')
            ->having('ulasan_count', '>=', $minReviews)
            ->get();

        if ($tempatList->isEmpty()) {
            return 0;
        }

        // Step 1: Collect raw values for each criterion
        $matrix = $this->buildDecisionMatrix($tempatList);

        // Step 2: Normalize the matrix
        $normalized = $this->normalizeMatrix($matrix);

        // Step 3: Calculate weighted scores
        $results = $this->calculateWeightedScores($normalized, $tempatList);

        // Step 4: Rank and save
        $this->saveResults($results, $userId);

        return count($results);
    }

    /**
     * Build the decision matrix with raw values
     */
    protected function buildDecisionMatrix($tempatList): array
    {
        $matrix = [];

        foreach ($tempatList as $tempat) {
            $avgRating = $tempat->ulasan()->avg('rating') ?? 0;

            // Sentiment score: ratio of positive sentiments
            $totalSentimen = $tempat->analisisSentimen()->count();
            $positifCount = $tempat->analisisSentimen()
                ->where('label_sentimen', 'positif')
                ->count();
            $skorSentimen = $totalSentimen > 0 ? ($positifCount / $totalSentimen) : 0.5;

            // Price score — handle null harga safely
            $hargaMin = (float) ($tempat->harga_min ?? 0);
            $hargaMax = (float) ($tempat->harga_max ?? $hargaMin);
            $avgHarga = ($hargaMin + $hargaMax) / 2;
            if ($avgHarga <= 0) $avgHarga = 50000; // default 50k jika tidak ada harga

            // Popularity: number of reviews
            $popularitas = $tempat->ulasan()->count();

            // Recency: days since last review (newer is better)
            $lastReview = $tempat->ulasan()->latest()->first();
            $daysSinceLastReview = $lastReview
                ? Carbon::now()->diffInDays($lastReview->created_at)
                : 365;
            $kebaruan = max(1, 365 - $daysSinceLastReview);

            $matrix[$tempat->id] = [
                'rating' => $avgRating,
                'sentimen' => $skorSentimen,
                'harga' => $avgHarga,
                'popularitas' => $popularitas,
                'kebaruan' => $kebaruan,
            ];
        }

        return $matrix;
    }

    /**
     * Normalize the decision matrix
     */
    protected function normalizeMatrix(array $matrix): array
    {
        if (empty($matrix)) return [];

        $criteria = ['rating', 'sentimen', 'harga', 'popularitas', 'kebaruan'];
        $normalized = [];

        foreach ($criteria as $criterion) {
            $values = array_column($matrix, $criterion);
            $max = max($values);
            $min = min($values);

            foreach ($matrix as $tempatId => $row) {
                $type = $this->criteriaTypes[$criterion] ?? 'benefit';

                if ($type === 'benefit') {
                    // Benefit: Rij = Xij / Max(Xij)
                    $normalized[$tempatId][$criterion] = $max > 0
                        ? $row[$criterion] / $max
                        : 0;
                } else {
                    // Cost: Rij = Min(Xij) / Xij
                    $normalized[$tempatId][$criterion] = $row[$criterion] > 0
                        ? $min / $row[$criterion]
                        : 0;
                }
            }
        }

        return $normalized;
    }

    /**
     * Calculate weighted scores (SAW formula: Vi = Σ(Wj * Rij))
     */
    protected function calculateWeightedScores(array $normalized, $tempatList): array
    {
        $results = [];

        foreach ($tempatList as $tempat) {
            if (!isset($normalized[$tempat->id])) continue;

            $row = $normalized[$tempat->id];

            $skorRating = $row['rating'] * $this->weights['rating'];
            $skorSentimen = $row['sentimen'] * $this->weights['sentimen'];
            $skorHarga = $row['harga'] * $this->weights['harga'];
            $skorPopularitas = $row['popularitas'] * $this->weights['popularitas'];
            $skorKebaruan = $row['kebaruan'] * $this->weights['kebaruan'];

            $skorFinal = $skorRating + $skorSentimen + $skorHarga + $skorPopularitas + $skorKebaruan;

            $results[] = [
                'tempat_id' => $tempat->id,
                'skor_rating' => round($skorRating, 4),
                'skor_sentimen' => round($skorSentimen, 4),
                'skor_harga' => round($skorHarga, 4),
                'skor_popularitas' => round($skorPopularitas, 4),
                'skor_kebaruan' => round($skorKebaruan, 4),
                'skor_saw_final' => round($skorFinal, 4),
            ];
        }

        // Sort by final score descending
        usort($results, fn($a, $b) => $b['skor_saw_final'] <=> $a['skor_saw_final']);

        // Assign peringkat (rank)
        foreach ($results as $i => &$result) {
            $result['peringkat'] = $i + 1;
        }

        return $results;
    }

    /**
     * Save results to database
     */
    protected function saveResults(array $results, ?string $userId = null): void
    {
        DB::transaction(function () use ($results, $userId) {
            // Delete old results for this scope
            RekomendasiSaw::where('user_id', $userId)->delete();

            $now = Carbon::now();

            foreach ($results as $result) {
                RekomendasiSaw::create([
                    'tempat_id' => $result['tempat_id'],
                    'user_id' => $userId,
                    'skor_rating' => $result['skor_rating'],
                    'skor_sentimen' => $result['skor_sentimen'],
                    'skor_harga' => $result['skor_harga'],
                    'skor_popularitas' => $result['skor_popularitas'],
                    'skor_kebaruan' => $result['skor_kebaruan'],
                    'skor_saw_final' => $result['skor_saw_final'],
                    'peringkat' => $result['peringkat'],
                    'dihitung_at' => $now,
                ]);
            }
        });
    }

    /**
     * Calculate SAW score for a single tempat
     */
    public function calculateSawScore(Tempat $tempat, ?array $weights = null): float
    {
        $w = $weights ?? $this->weights;

        $avgRating = $tempat->ulasan()->avg('rating') ?? 0;
        $totalSentimen = $tempat->analisisSentimen()->count();
        $positifCount = $tempat->analisisSentimen()->where('label_sentimen', 'positif')->count();
        $skorSentimen = $totalSentimen > 0 ? ($positifCount / $totalSentimen) : 0.5;
        $avgHarga = ($tempat->harga_min + $tempat->harga_max) / 2;
        $popularitas = $tempat->ulasan()->count();
        $lastReview = $tempat->ulasan()->latest()->first();
        $daysSince = $lastReview ? Carbon::now()->diffInDays($lastReview->created_at) : 365;
        $kebaruan = max(1, 365 - $daysSince);

        // Simplified normalization using expected ranges
        $nRating = min($avgRating / 5.0, 1.0);
        $nSentimen = $skorSentimen;
        $nHarga = $avgHarga > 0 ? min(50000 / $avgHarga, 1.0) : 0.5;
        $nPopularitas = min($popularitas / 100.0, 1.0);
        $nKebaruan = min($kebaruan / 365.0, 1.0);

        return round(
            ($nRating * $w['rating']) +
            ($nSentimen * $w['sentimen']) +
            ($nHarga * $w['harga']) +
            ($nPopularitas * $w['popularitas']) +
            ($nKebaruan * $w['kebaruan']),
            4
        );
    }

    /**
     * Update weights (admin feature)
     */
    public function updateWeights(array $newWeights): void
    {
        $this->weights = $newWeights;
    }

    /**
     * Get current weights
     */
    public function getWeights(): array
    {
        return $this->weights;
    }
}
