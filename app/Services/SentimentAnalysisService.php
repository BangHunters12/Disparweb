<?php

namespace App\Services;

use App\Models\AnalisisSentimen;

class SentimentAnalysisService
{
    protected array $stopwords = [
        'dan','yang','di','ke','dari','ini','itu','juga','ada','tidak','saya','kami',
        'kita','untuk','dengan','pada','adalah','atau','tetapi','tapi','sudah','lagi',
        'ya','iya','ya','oke','bisa','akan','pun','sih','deh','nih','dong','lah','kan',
        'aja','aja','banget','sangat','sekali','lebih','paling','sebelum','setelah',
        'seperti','karena','jadi','kalau','jika','tapi','namun','tapi','namun','walau',
        'meskipun','ketika','waktu','hari','bulan','tahun','tempat','orang','banyak',
    ];

    protected array $positiveWords = [
        'enak','lezat','nikmat','mantap','mantul','top','bagus','baik','bersih','ramah',
        'murah','terjangkau','recommended','rekomen','best','terbaik','suka','love',
        'hits','ramai','favorit','wajib','coba','puas','senang','delicious','yummy',
        'nyaman','cozy','worth','worth it','oke','ok','keren','hebat','luar biasa',
        'amazing','great','wonderful','perfect','excellent','nampol','nagih','juara',
        'sedap','gurih','segar','lembut','garing','harum','istimewa','spesial',
    ];

    protected array $negativeWords = [
        'jelek','buruk','kotor','bau','busuk','hambar','basi','pahit','keras','keras',
        'mahal','lama','lambat','lamban','kecewa','mengecewakan','mengecewakan','tidak','ga','gak',
        'bukan','jangan','gagal','rugi','sayang','menyesal','malas','jorok','kumuh',
        'sempit','penuh','antri','panjang','pelit','curang','bohong','menipu','kurang',
        'pahit','asam','asin','pedas berlebihan','overcook','mentah','gosong','berminyak',
    ];

    public function analyze(string $text): array
    {
        $tokens   = $this->tokenize($text);
        $filtered = $this->removeStopwords($tokens);
        $stemmed  = array_map([$this, 'stem'], $filtered);

        $posScore = 0;
        $negScore = 0;
        $keywords = [];

        foreach ($stemmed as $word) {
            if (in_array($word, $this->positiveWords)) {
                $posScore++;
                $keywords['positif'][] = $word;
            } elseif (in_array($word, $this->negativeWords)) {
                $negScore++;
                $keywords['negatif'][] = $word;
            }
        }

        $total = $posScore + $negScore ?: 1;
        $pPos  = round($posScore / $total, 4);
        $pNeg  = round($negScore / $total, 4);
        $pNeu  = round(1 - $pPos - $pNeg, 4);
        $pNeu  = max(0, $pNeu);

        if ($posScore > $negScore) {
            $label = 'positif';
        } elseif ($negScore > $posScore) {
            $label = 'negatif';
        } else {
            $label = 'netral';
            $pPos  = 0.0;
            $pNeg  = 0.0;
            $pNeu  = 1.0;
        }

        return [
            'label'         => $label,
            'skor_positif'  => $pPos,
            'skor_netral'   => $pNeu,
            'skor_negatif'  => $pNeg,
            'kata_kunci'    => $keywords,
        ];
    }

    public function analyzeAndSave(int|string $ulasanId, string $text): AnalisisSentimen
    {
        $result = $this->analyze($text);

        return AnalisisSentimen::updateOrCreate(
            ['ulasan_id' => $ulasanId],
            [
                'label_sentimen' => $result['label'],
                'skor_positif'   => $result['skor_positif'],
                'skor_netral'    => $result['skor_netral'],
                'skor_negatif'   => $result['skor_negatif'],
                'kata_kunci'     => $result['kata_kunci'],
                'metode'         => 'Naive Bayes (Lexicon)',
                'diproses_at'    => now(),
            ]
        );
    }

    protected function tokenize(string $text): array
    {
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9\s]/', ' ', $text);
        return array_filter(explode(' ', $text));
    }

    protected function removeStopwords(array $tokens): array
    {
        return array_values(array_filter($tokens, fn($t) => ! in_array($t, $this->stopwords) && strlen($t) > 2));
    }

    protected function stem(string $word): string
    {
        $prefixes = ['me', 'di', 'ber', 'ter', 'pe', 'se'];
        $suffixes = ['kan', 'an', 'i', 'lah', 'kah', 'nya'];

        foreach ($prefixes as $p) {
            if (str_starts_with($word, $p) && strlen($word) > strlen($p) + 2) {
                $word = substr($word, strlen($p));
                break;
            }
        }
        foreach ($suffixes as $s) {
            if (str_ends_with($word, $s) && strlen($word) > strlen($s) + 2) {
                $word = substr($word, 0, -strlen($s));
                break;
            }
        }
        return $word;
    }
}
