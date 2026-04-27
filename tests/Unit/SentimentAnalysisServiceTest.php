<?php

namespace Tests\Unit\Services;

use App\Services\SentimentAnalysisService;
use Tests\TestCase;

class SentimentAnalysisServiceTest extends TestCase
{
    protected SentimentAnalysisService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new SentimentAnalysisService();
    }

    public function test_analyze_returns_correct_structure(): void
    {
        $result = $this->service->analyze('Makanan enak dan tempat nyaman');

        $this->assertArrayHasKey('label_sentimen', $result);
        $this->assertArrayHasKey('skor_positif', $result);
        $this->assertArrayHasKey('skor_netral', $result);
        $this->assertArrayHasKey('skor_negatif', $result);
        $this->assertArrayHasKey('metode', $result);
        $this->assertArrayHasKey('kata_kunci', $result);
    }

    public function test_positive_text_returns_positif_label(): void
    {
        $result = $this->service->analyze('Enak sekali! Tempat bagus, pelayanan ramah dan nyaman. Pasti balik lagi!');
        $this->assertEquals('positif', $result['label_sentimen']);
    }

    public function test_negative_text_returns_negatif_label(): void
    {
        $result = $this->service->analyze('Pelayanan lambat sekali dan makanan tidak enak, sangat mengecewakan dan kotor');
        $this->assertEquals('negatif', $result['label_sentimen']);
    }

    public function test_scores_sum_to_approximately_one(): void
    {
        $result = $this->service->analyze('Tempatnya cukup standar saja biasa saja');
        $sum = $result['skor_positif'] + $result['skor_netral'] + $result['skor_negatif'];
        $this->assertEqualsWithDelta(1.0, $sum, 0.01);
    }

    public function test_preprocess_lowercases_and_trims(): void
    {
        $processed = $this->service->preprocess('  MAKANAN Enak!!! ');
        $this->assertEquals('makanan enak', $processed);
    }

    public function test_tokenize_splits_words(): void
    {
        $tokens = $this->service->tokenize('enak sekali mantap');
        $this->assertCount(3, $tokens);
        $this->assertContains('enak', $tokens);
    }

    public function test_remove_stopwords_removes_indonesian_stopwords(): void
    {
        $tokens = ['yang', 'enak', 'dan', 'bagus', 'di', 'sini'];
        $filtered = $this->service->removeStopwords($tokens);
        $this->assertNotContains('yang', $filtered);
        $this->assertNotContains('dan', $filtered);
        $this->assertContains('enak', $filtered);
        $this->assertContains('bagus', $filtered);
    }

    public function test_stemming_removes_suffix(): void
    {
        $stemmed = $this->service->stem('makanan');
        $this->assertEquals('mak', $stemmed);
    }

    public function test_negation_flips_sentiment(): void
    {
        $positive = $this->service->analyze('Makanan enak dan bagus');
        $negated  = $this->service->analyze('Makanan tidak enak dan tidak bagus');
        $this->assertGreaterThan($negated['skor_positif'], $positive['skor_positif']);
    }

    public function test_empty_text_returns_neutral(): void
    {
        $result = $this->service->analyze('');
        $this->assertIsArray($result);
        $this->assertArrayHasKey('label_sentimen', $result);
    }

    public function test_extract_keywords_returns_sentiment_words(): void
    {
        $tokens   = ['enak', 'bagus', 'pelayanan', 'nyaman'];
        $keywords = $this->service->extractKeywords($tokens);
        $this->assertContains('enak', $keywords);
        $this->assertContains('bagus', $keywords);
        $this->assertContains('nyaman', $keywords);
    }
}
