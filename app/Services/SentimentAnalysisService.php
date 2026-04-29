<?php

namespace App\Services;

use Illuminate\Support\Str;

class SentimentAnalysisService
{
    /**
     * Daftar stopwords Bahasa Indonesia
     */
    protected array $stopwords = [
        'yang', 'dan', 'di', 'dari', 'untuk', 'dengan', 'adalah', 'ini', 'itu',
        'pada', 'ke', 'akan', 'tidak', 'juga', 'sudah', 'saya', 'kami', 'kita',
        'ada', 'atau', 'bisa', 'oleh', 'karena', 'saat', 'lagi', 'agar', 'jadi',
        'hanya', 'tapi', 'mereka', 'dia', 'telah', 'belum', 'kalau', 'mau',
        'bukan', 'masih', 'saja', 'seperti', 'hal', 'apa', 'kali', 'pun',
        'maka', 'sedang', 'punya', 'lalu', 'dulu', 'begitu', 'bagi', 'hingga',
        'antara', 'selama', 'sekali', 'daripada', 'sebelum', 'sesudah',
        'atas', 'bawah', 'tengah', 'luar', 'dalam', 'semua', 'tiap', 'setiap',
        'para', 'sama', 'tersebut', 'yaitu', 'yakni', 'kemudian', 'namun',
        'melainkan', 'maupun', 'walaupun', 'meskipun', 'bahwa', 'sebab',
        'sehingga', 'supaya', 'ketika', 'sambil', 'seraya', 'apabila',
        'andaikan', 'kiranya', 'barangkali', 'seolah', 'jika', 'bila',
        'nya', 'lah', 'kah', 'pun', 'dong', 'ya', 'sih', 'kan', 'kok',
        'eh', 'oh', 'deh', 'tuh', 'nah', 'nih', 'gitu', 'gini',
        'sangat', 'amat', 'sekali', 'paling', 'lebih', 'agak', 'cukup',
        'hampir', 'kurang', 'terlalu', 'jarang',
    ];

    /**
     * Kata-kata positif Bahasa Indonesia
     */
    protected array $positiveWords = [
        'bagus', 'baik', 'enak', 'lezat', 'nikmat', 'sempurna', 'indah', 'cantik',
        'bersih', 'nyaman', 'ramah', 'murah', 'mantap', 'keren', 'hebat', 'luar biasa',
        'memuaskan', 'puas', 'senang', 'suka', 'cinta', 'rekomendasi', 'recommended',
        'terbaik', 'top', 'oke', 'favorit', 'juara', 'istimewa', 'wow', 'kece',
        'wajib', 'cocok', 'strategis', 'lengkap', 'cepat', 'segar', 'fresh',
        'mewah', 'comfortable', 'good', 'great', 'excellent', 'amazing', 'awesome',
        'best', 'love', 'nice', 'perfect', 'beautiful', 'clean', 'friendly',
        'delicious', 'tasty', 'worth', 'affordable', 'terkenal', 'populer',
        'nendang', 'mantul', 'gurih', 'renyah', 'empuk', 'harum', 'wangi',
        'lembut', 'halus', 'rapih', 'tertata', 'asyik', 'menyenangkan',
        'teratur', 'terawat', 'modern', 'unik', 'menarik', 'recommended',
        'rekomen', 'pokoknya', 'juara', 'jempol', 'top markotop', 'josss',
        'mantab', 'uenak', 'pol', 'ngangenin', 'bikin nagih', 'nagih',
    ];

    /**
     * Kata-kata negatif Bahasa Indonesia
     */
    protected array $negativeWords = [
        'buruk', 'jelek', 'kotor', 'bau', 'busuk', 'pahit', 'tawar', 'hambar',
        'mahal', 'lambat', 'lama', 'kasar', 'kecewa', 'mengecewakan', 'payah',
        'gagal', 'rusak', 'hancur', 'sampah', 'parah', 'ampas', 'zonk',
        'tidak enak', 'kurang', 'biasa', 'jorok', 'kumuh', 'bocor', 'bising',
        'panas', 'sempit', 'gelap', 'basi', 'amis', 'keras', 'alot',
        'bad', 'worst', 'terrible', 'horrible', 'awful', 'dirty', 'slow',
        'expensive', 'rude', 'disappointed', 'disappointing', 'poor', 'waste',
        'overpriced', 'boring', 'cold', 'stale', 'noisy',
        'rugi', 'menyesal', 'kapok', 'ogah', 'males', 'sebal', 'kesal',
        'jijik', 'bosan', 'sumpek', 'gerah', 'berantakan', 'acak-acakan',
        'asin', 'gosong', 'mentah', 'beku', 'dingin', 'anyir', 'apek',
    ];

    /**
     * Negation words that flip sentiment
     */
    protected array $negationWords = [
        'tidak', 'bukan', 'belum', 'jangan', 'tanpa', 'tak', 'engga',
        'enggak', 'nggak', 'gak', 'ga', 'kagak', 'ndak', 'nda',
    ];

    /**
     * Analyze sentiment of a given text
     */
    public function analyze(string $text): array
    {
        $processed = $this->preprocess($text);
        $tokens = $this->tokenize($processed);
        $filtered = $this->removeStopwords($tokens);

        // Calculate scores using keyword matching + Naive Bayes approach
        $scores = $this->calculateScores($filtered, $tokens);
        $keywords = $this->extractKeywords($filtered);

        $label = $this->determineLabel($scores);

        return [
            'label_sentimen' => $label,
            'skor_positif' => round($scores['positif'], 4),
            'skor_netral' => round($scores['netral'], 4),
            'skor_negatif' => round($scores['negatif'], 4),
            'metode' => 'Naive Bayes',
            'kata_kunci' => $keywords,
        ];
    }

    /**
     * Preprocess text: lowercase, remove special chars
     */
    public function preprocess(string $text): string
    {
        $text = Str::lower($text);
        $text = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $text);
        $text = preg_replace('/\s+/', ' ', $text);

        return trim($text);
    }

    /**
     * Tokenize text into words
     */
    public function tokenize(string $text): array
    {
        return array_filter(explode(' ', $text), fn ($w) => strlen($w) > 1);
    }

    /**
     * Remove stopwords from tokens
     */
    public function removeStopwords(array $tokens): array
    {
        return array_values(array_filter(
            $tokens,
            fn ($token) => ! in_array($token, $this->stopwords)
        ));
    }

    /**
     * Simple stemming for Indonesian (remove common suffixes/prefixes)
     */
    public function stem(string $word): string
    {
        // Remove common suffixes
        $word = preg_replace('/(kan|an|i|lah|kah|nya|mu|ku)$/', '', $word);
        // Remove common prefixes
        $word = preg_replace('/^(me|mem|men|meny|meng|ber|di|ke|se|per|ter|pe)/', '', $word);

        return $word;
    }

    /**
     * Calculate sentiment scores using Naive Bayes-inspired approach
     */
    protected function calculateScores(array $filteredTokens, array $allTokens): array
    {
        $positiveCount = 0;
        $negativeCount = 0;
        $totalWords = count($filteredTokens);

        if ($totalWords === 0) {
            return ['positif' => 0.3333, 'netral' => 0.3334, 'negatif' => 0.3333];
        }

        // Check for negation context
        $negationActive = false;

        foreach ($allTokens as $i => $token) {
            if (in_array($token, $this->negationWords)) {
                $negationActive = true;

                continue;
            }

            $isPositive = $this->isPositiveWord($token);
            $isNegative = $this->isNegativeWord($token);

            if ($negationActive) {
                // Flip sentiment
                if ($isPositive) {
                    $negativeCount += 1.5;
                } elseif ($isNegative) {
                    $positiveCount += 1.0;
                }
                $negationActive = false;
            } else {
                if ($isPositive) {
                    $positiveCount += 1.0;
                }
                if ($isNegative) {
                    $negativeCount += 1.0;
                }
            }
        }

        // Naive Bayes-style probability calculation
        $total = $positiveCount + $negativeCount + 1; // Laplace smoothing
        $pPositif = ($positiveCount + 0.5) / $total;
        $pNegatif = ($negativeCount + 0.5) / $total;

        // Normalize
        $sum = $pPositif + $pNegatif;
        if ($sum > 0) {
            $pPositif = $pPositif / $sum;
            $pNegatif = $pNegatif / $sum;
        }

        // Determine neutral balance
        $confidence = abs($pPositif - $pNegatif);
        $pNetral = max(0, 1.0 - $confidence - 0.3);

        // Final normalization
        $total = $pPositif + $pNetral + $pNegatif;
        if ($total > 0) {
            $pPositif /= $total;
            $pNetral /= $total;
            $pNegatif /= $total;
        }

        return [
            'positif' => $pPositif,
            'netral' => $pNetral,
            'negatif' => $pNegatif,
        ];
    }

    /**
     * Check if word is positive (with stemming fallback)
     */
    protected function isPositiveWord(string $word): bool
    {
        if (in_array($word, $this->positiveWords)) {
            return true;
        }
        $stemmed = $this->stem($word);

        return in_array($stemmed, $this->positiveWords);
    }

    /**
     * Check if word is negative (with stemming fallback)
     */
    protected function isNegativeWord(string $word): bool
    {
        if (in_array($word, $this->negativeWords)) {
            return true;
        }
        $stemmed = $this->stem($word);

        return in_array($stemmed, $this->negativeWords);
    }

    /**
     * Extract important keywords from tokens
     */
    public function extractKeywords(array $tokens): array
    {
        $keywords = [];
        foreach ($tokens as $token) {
            if ($this->isPositiveWord($token) || $this->isNegativeWord($token)) {
                $keywords[] = $token;
            }
        }

        return array_unique($keywords);
    }

    /**
     * Determine final label from scores
     */
    protected function determineLabel(array $scores): string
    {
        $max = max($scores);

        if ($scores['positif'] === $max) {
            return 'positif';
        }
        if ($scores['negatif'] === $max) {
            return 'negatif';
        }

        return 'netral';
    }
}
