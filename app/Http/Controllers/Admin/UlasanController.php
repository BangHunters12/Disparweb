<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restoran;
use App\Models\Ulasan;
use App\Services\SentimentAnalysisService;
use Illuminate\Http\Request;

class UlasanController extends Controller
{
    public function index(Request $request)
    {
        $query = Ulasan::with(['restoran', 'analisisSentimen'])->latest();

        if ($request->filled('restoran')) {
            $query->where('restoran_id', $request->restoran);
        }
        if ($request->filled('platform')) {
            $query->where('platform_sumber', $request->platform);
        }
        if ($request->filled('sentimen')) {
            $query->whereHas('analisisSentimen', fn($q) => $q->where('label_sentimen', $request->sentimen));
        }
        if ($request->filled('search')) {
            $query->where('teks_ulasan', 'like', '%' . $request->search . '%')
                ->orWhere('nama_reviewer', 'like', '%' . $request->search . '%');
        }

        $ulasan = $query->paginate(20)->withQueryString();
        $restoranList = Restoran::aktif()->orderBy('nama_usaha')->get(['id', 'nama_usaha']);

        return view('admin.ulasan.index', compact('ulasan', 'restoranList'));
    }

    public function create()
    {
        $restoranList = Restoran::aktif()->orderBy('nama_usaha')->get(['id', 'nama_usaha']);
        return view('admin.ulasan.form', compact('restoranList'));
    }

    public function store(Request $request, SentimentAnalysisService $sentiment)
    {
        $data = $request->validate([
            'restoran_id' => 'required|exists:restoran,id',
            'nama_reviewer' => 'required|string|max:100',
            'rating' => 'required|numeric|min:1|max:5',
            'teks_ulasan' => 'required|string|min:5',
            'platform_sumber' => 'required|in:app,gmaps,dispar',
            'tgl_kunjungan' => 'nullable|date',
            'is_visible' => 'boolean',
        ]);

        $data['is_visible'] = $request->boolean('is_visible', true);

        $ulasan = Ulasan::create($data);

        // Langsung analisis sentimen (sync)
        $sentiment->analyzeAndSave($ulasan->id, $ulasan->teks_ulasan);

        // Update avg_rating restoran
        $this->updateRestoranStats($ulasan->restoran_id);

        return redirect()->route('admin.ulasan.index')
            ->with('success', 'Ulasan berhasil ditambahkan dan dianalisis.');
    }

    public function edit(string $id)
    {
        $ulasan = Ulasan::findOrFail($id);
        $restoranList = Restoran::aktif()->orderBy('nama_usaha')->get(['id', 'nama_usaha']);
        return view('admin.ulasan.form', compact('ulasan', 'restoranList'));
    }

    public function update(Request $request, string $id, SentimentAnalysisService $sentiment)
    {
        $ulasan = Ulasan::findOrFail($id);

        $data = $request->validate([
            'restoran_id' => 'required|exists:restoran,id',
            'nama_reviewer' => 'required|string|max:100',
            'rating' => 'required|numeric|min:1|max:5',
            'teks_ulasan' => 'required|string|min:5',
            'platform_sumber' => 'required|in:app,gmaps,dispar',
            'tgl_kunjungan' => 'nullable|date',
            'is_visible' => 'boolean',
        ]);

        $data['is_visible'] = $request->boolean('is_visible', true);
        $ulasan->update($data);

        // Re-analisis jika teks berubah
        if ($ulasan->wasChanged('teks_ulasan')) {
            $ulasan->analisisSentimen()->delete();
            $sentiment->analyzeAndSave($ulasan->id, $ulasan->teks_ulasan);
        }

        $this->updateRestoranStats($ulasan->restoran_id);

        return redirect()->route('admin.ulasan.index')
            ->with('success', 'Ulasan berhasil diperbarui.');
    }

    public function toggleVisibility(string $id)
    {
        $ulasan = Ulasan::findOrFail($id);
        $ulasan->update(['is_visible' => !$ulasan->is_visible]);
        $this->updateRestoranStats($ulasan->restoran_id);
        return back()->with('success', 'Status tampil ulasan diperbarui.');
    }

    public function destroy(string $id)
    {
        $ulasan = Ulasan::findOrFail($id);
        $rid = $ulasan->restoran_id;
        $ulasan->delete();
        $this->updateRestoranStats($rid);
        return back()->with('success', 'Ulasan berhasil dihapus.');
    }

    public function reanalyze(string $id, SentimentAnalysisService $sentiment)
    {
        $ulasan = Ulasan::findOrFail($id);
        $ulasan->analisisSentimen()->delete();
        $sentiment->analyzeAndSave($ulasan->id, $ulasan->teks_ulasan);
        return back()->with('success', 'Analisis sentimen selesai.');
    }

    public function bulkReanalyze(Request $request, SentimentAnalysisService $sentiment)
    {
        $request->validate(['ids' => 'required|array']);
        $ulasanList = Ulasan::whereIn('id', $request->ids)->get();
        foreach ($ulasanList as $u) {
            $u->analisisSentimen()->delete();
            $sentiment->analyzeAndSave($u->id, $u->teks_ulasan);
        }
        return back()->with('success', count($request->ids) . ' ulasan berhasil dianalisis ulang.');
    }

    public function showImportCsv()
    {
        return view('admin.ulasan.import-csv');
    }

    public function importCsv(Request $request, SentimentAnalysisService $sentiment)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $file = $request->file('file');
        $platform = $request->input('platform_sumber', 'dispar');
        $doAnalyze = $request->boolean('auto_analyze', true);

        $handle = fopen($file->getRealPath(), 'r');
        $header = fgetcsv($handle); // baris header

        // Normalize header
        $header = array_map(fn($h) => trim(strtolower(str_replace(' ', '_', $h))), $header);

        $berhasil = 0;
        $gagal = 0;
        $dianalisis = 0;
        $errors = [];
        $row = 1;

        // Cache restoran names
        $restoranCache = Restoran::aktif()->get(['id', 'nama_usaha'])
            ->keyBy(fn($r) => strtolower(trim($r->nama_usaha)));

        while (($cols = fgetcsv($handle)) !== false) {
            $row++;
            if (count($cols) < 4) {
                $gagal++;
                $errors[] = "Baris {$row}: kolom kurang.";
                continue;
            }

            $data = array_combine($header, array_pad($cols, count($header), null));

            $namaRestoran = strtolower(trim($data['nama_restoran'] ?? ''));
            $restoran = $restoranCache->get($namaRestoran);

            if (!$restoran) {
                // Coba partial match
                $restoran = $restoranCache->first(
                    fn($r) => str_contains(strtolower($r->nama_usaha), $namaRestoran)
                    || str_contains($namaRestoran, strtolower($r->nama_usaha))
                );
            }

            if (!$restoran) {
                $gagal++;
                $errors[] = "Baris {$row}: Restoran '{$data['nama_restoran']}' tidak ditemukan.";
                continue;
            }

            $teks = trim($data['teks_ulasan'] ?? '');
            if (empty($teks)) {
                $gagal++;
                $errors[] = "Baris {$row}: Teks ulasan kosong.";
                continue;
            }

            $rating = (float) ($data['rating'] ?? 3);
            if ($rating < 1 || $rating > 5)
                $rating = max(1, min(5, $rating));

            try {
                $ulasan = Ulasan::create([
                    'restoran_id' => $restoran->id,
                    'nama_reviewer' => trim($data['nama_reviewer'] ?? 'Anonim'),
                    'rating' => $rating,
                    'teks_ulasan' => $teks,
                    'platform_sumber' => $platform,
                    'tgl_kunjungan' => !empty($data['tgl_kunjungan']) ? $data['tgl_kunjungan'] : null,
                    'is_visible' => true,
                ]);

                if ($doAnalyze) {
                    $sentiment->analyzeAndSave($ulasan->id, $teks);
                    $dianalisis++;
                }

                $berhasil++;
            } catch (\Throwable $e) {
                $gagal++;
                $errors[] = "Baris {$row}: " . $e->getMessage();
            }
        }

        fclose($handle);

        // Update stats semua restoran yang terdampak
        foreach ($restoranCache as $restoran) {
            $this->updateRestoranStats($restoran->id);
        }

        return redirect()->route('admin.ulasan.import-csv')
            ->with('import_result', compact('berhasil', 'gagal', 'dianalisis', 'errors'))
            ->with('success', "Import selesai: {$berhasil} ulasan berhasil ditambahkan.");
    }

    public function csvTemplate()
    {
        $restoran = Restoran::aktif()->orderBy('nama_usaha')->take(3)->pluck('nama_usaha');

        $rows = ["nama_restoran,nama_reviewer,rating,teks_ulasan,tgl_kunjungan"];
        $contoh = [
            ["Enak banget, kuahnya gurih dan segar!", 5, '2024-03-15'],
            ["Porsinya besar harga terjangkau, recommended!", 4, '2024-03-10'],
            ["Biasa saja, tidak ada yang istimewa", 3, ''],
        ];

        foreach ($restoran as $i => $nama) {
            [$teks, $rating, $tgl] = $contoh[$i] ?? $contoh[0];
            $rows[] = "\"{$nama}\",Reviewer {$i}," . $rating . ",\"" . $teks . "\"," . $tgl;
        }

        if ($restoran->isEmpty()) {
            $rows[] = '"Warung Soto Bondowoso",Budi S,5,"Enak banget!",2024-03-15';
        }

        return response(implode("\n", $rows), 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename=template-ulasan-bondowisata.csv',
        ]);
    }
}

