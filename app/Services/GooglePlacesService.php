<?php

namespace App\Services;

use App\Models\ImportLog;
use App\Models\Kecamatan;
use App\Models\Restoran;
use App\Models\Ulasan;
use App\Jobs\AnalyzeSentimentJob;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GooglePlacesService
{
    protected string $apiKey;
    protected array $defaultLocation;

    public function __construct()
    {
        $this->apiKey          = config('google.places_key');
        $this->defaultLocation = config('google.default_location');
    }

    public function searchRestaurants(string $keyword): array
    {
        $response = Http::timeout(15)->get('https://maps.googleapis.com/maps/api/place/textsearch/json', [
            'query'    => $keyword,
            'location' => $this->defaultLocation['lat'] . ',' . $this->defaultLocation['lng'],
            'radius'   => $this->defaultLocation['radius'],
            'type'     => config('google.search_types'),
            'language' => config('google.search_language'),
            'key'      => $this->apiKey,
        ]);

        if (! $response->successful()) {
            throw new \RuntimeException('Google Places API error: ' . $response->status());
        }

        $data = $response->json();
        if (($data['status'] ?? '') === 'REQUEST_DENIED') {
            throw new \RuntimeException('API key tidak valid atau Places API belum diaktifkan: ' . ($data['error_message'] ?? ''));
        }

        return $data['results'] ?? [];
    }

    public function getPlaceDetail(string $placeId): array
    {
        $response = Http::timeout(15)->get('https://maps.googleapis.com/maps/api/place/details/json', [
            'place_id' => $placeId,
            'fields'   => 'name,formatted_address,geometry,rating,user_ratings_total,formatted_phone_number,opening_hours,photos,price_level,reviews,url,website',
            'language' => config('google.search_language'),
            'key'      => $this->apiKey,
        ]);

        return $response->json()['result'] ?? [];
    }

    public function buildImportPreview(array $results): array
    {
        $existingKodes = Restoran::whereNotNull('kode_gmaps')->pluck('kode_gmaps')->flip();

        return array_map(function ($r) use ($existingKodes) {
            $photoUrl = null;
            if (! empty($r['photos'][0]['photo_reference'])) {
                $photoUrl = "https://maps.googleapis.com/maps/api/place/photo?maxwidth=400&photoreference={$r['photos'][0]['photo_reference']}&key={$this->apiKey}";
            }

            return [
                'place_id'      => $r['place_id'],
                'nama'          => $r['name'],
                'alamat'        => $r['formatted_address'],
                'rating'        => $r['rating'] ?? 0,
                'total_ulasan'  => $r['user_ratings_total'] ?? 0,
                'price_level'   => $r['price_level'] ?? null,
                'foto'          => $photoUrl,
                'sudah_ada'     => isset($existingKodes[$r['place_id']]),
            ];
        }, $results);
    }

    public function importPlace(array $placeData, ?string $adminId = null): ?Restoran
    {
        $detail = $this->getPlaceDetail($placeData['place_id']);
        if (empty($detail)) return null;

        // Resolve kecamatan (default ke kecamatan pertama jika tidak ditemukan)
        $kecamatan = Kecamatan::first();

        $hargaMap = [1 => [5000, 25000], 2 => [25000, 50000], 3 => [50000, 150000], 4 => [150000, 500000]];
        $priceLevel = $detail['price_level'] ?? null;
        $harga = $hargaMap[$priceLevel] ?? [null, null];

        $restoran = Restoran::updateOrCreate(
            ['kode_gmaps' => $placeData['place_id']],
            [
                'kecamatan_id' => $kecamatan?->id,
                'nama_usaha'   => $detail['name'],
                'alamat'       => $detail['formatted_address'],
                'latitude'     => $detail['geometry']['location']['lat'] ?? null,
                'longitude'    => $detail['geometry']['location']['lng'] ?? null,
                'no_telepon'   => $detail['formatted_phone_number'] ?? null,
                'website'      => $detail['website'] ?? null,
                'gmaps_url'    => $detail['url'] ?? null,
                'harga_min'    => $harga[0],
                'harga_max'    => $harga[1],
                'avg_rating'   => $detail['rating'] ?? 0,
                'total_ulasan' => $detail['user_ratings_total'] ?? 0,
                'status'       => 'aktif',
                'sumber'       => 'gmaps',
                'jam_buka'     => $this->parseOpeningHours($detail['opening_hours']['periods'] ?? []),
            ]
        );

        // Fetch & store photos
        if (! empty($detail['photos'])) {
            $this->fetchAndStorePhotos(
                array_slice($detail['photos'], 0, config('google.max_photos', 5)),
                $restoran->id
            );
            $restoran->refresh();
        }

        // Import reviews
        if (! empty($detail['reviews'])) {
            foreach (array_slice($detail['reviews'], 0, config('google.max_reviews', 5)) as $rev) {
                $ulasan = Ulasan::create([
                    'restoran_id'    => $restoran->id,
                    'nama_reviewer'  => $rev['author_name'] ?? 'Google User',
                    'foto_reviewer'  => $rev['profile_photo_url'] ?? null,
                    'rating'         => $rev['rating'] ?? 3,
                    'teks_ulasan'    => $rev['text'] ?? '',
                    'platform_sumber'=> 'gmaps',
                ]);
                AnalyzeSentimentJob::dispatch($ulasan);
            }
        }

        return $restoran;
    }

    public function fetchAndStorePhotos(array $photos, string $restoranId): array
    {
        $stored = [];
        $dir    = "restoran/{$restoranId}";

        foreach ($photos as $i => $photo) {
            try {
                $ref      = $photo['photo_reference'];
                $url      = "https://maps.googleapis.com/maps/api/place/photo?maxwidth=800&photoreference={$ref}&key={$this->apiKey}";
                $content  = Http::timeout(20)->get($url)->body();
                $filename = $dir . '/gmaps_' . $i . '.jpg';
                Storage::disk('public')->put($filename, $content);
                $stored[] = $filename;
            } catch (\Throwable $e) {
                Log::warning("Failed to fetch GMaps photo: {$e->getMessage()}");
            }
        }

        if (! empty($stored)) {
            $restoran = Restoran::find($restoranId);
            if ($restoran) {
                $restoran->update([
                    'foto_utama'  => $stored[0],
                    'foto_galeri' => array_slice($stored, 1),
                ]);
            }
        }

        return $stored;
    }

    protected function parseOpeningHours(array $periods): array
    {
        $days = ['minggu', 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];
        $jam  = [];
        foreach ($periods as $p) {
            $idx     = $p['open']['day'] ?? 0;
            $buka    = isset($p['open']['time'])  ? substr($p['open']['time'],  0, 2) . ':' . substr($p['open']['time'],  2) : '00:00';
            $tutup   = isset($p['close']['time']) ? substr($p['close']['time'], 0, 2) . ':' . substr($p['close']['time'], 2) : '23:59';
            $jam[]   = ['hari' => $days[$idx] ?? 'senin', 'buka' => $buka, 'tutup' => $tutup];
        }
        return $jam;
    }
}
