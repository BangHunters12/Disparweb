<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\AnalyzeSentimentJob;
use App\Models\Kategori;
use App\Models\Kecamatan;
use App\Models\Tempat;
use App\Models\Ulasan;
use App\Models\UlasanModerationLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;

class TempatController extends Controller
{
    public function index(Request $request)
    {
        $query = Tempat::with(['kategori', 'kecamatan', 'latestUlasan.user'])
            ->withCount('ulasan')
            ->withAvg('ulasan', 'rating');

        if ($request->filled('search')) {
            $query->where('nama_usaha', 'like', '%'.$request->search.'%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('kategori')) {
            $query->whereHas('kategori', fn ($q) => $q->where('jenis', $request->kategori));
        }

        $tempat = $query->latest()->paginate(12);
        $kategoriList = Kategori::all();

        return view('admin.tempat.index', compact('tempat', 'kategoriList'));
    }

    public function show(string $id)
    {
        $tempat = Tempat::with([
            'kategori',
            'kecamatan',
            'ulasan' => fn ($q) => $q->with(['user', 'analisisSentimen'])->latest(),
        ])->findOrFail($id);

        $totalUlasan = $tempat->ulasan->count();
        $rataRating = $tempat->ulasan->avg('rating') ?? 0;
        $distribusiRating = collect(range(5, 1))->mapWithKeys(
            fn ($rating) => [$rating => $tempat->ulasan->where('rating', $rating)->count()]
        );
        $sentimen = [
            'positif' => $tempat->ulasan->filter(fn ($u) => $u->analisisSentimen?->label_sentimen === 'positif')->count(),
            'netral' => $tempat->ulasan->filter(fn ($u) => $u->analisisSentimen?->label_sentimen === 'netral')->count(),
            'negatif' => $tempat->ulasan->filter(fn ($u) => $u->analisisSentimen?->label_sentimen === 'negatif')->count(),
            'pending' => $tempat->ulasan->filter(fn ($u) => ! $u->analisisSentimen)->count(),
        ];

        return view('admin.tempat.show', compact(
            'tempat',
            'totalUlasan',
            'rataRating',
            'distribusiRating',
            'sentimen'
        ));
    }

    public function create()
    {
        $kategoriList = Kategori::all();
        $kecamatanList = Kecamatan::orderBy('nama')->get();

        return view('admin.tempat.create', compact('kategoriList', 'kecamatanList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_usaha' => 'required|string|max:200',
            'kategori_id' => 'required|exists:kategori,id',
            'kecamatan_id' => 'required|exists:kecamatan,id',
            'alamat' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'no_telepon' => 'nullable|string|max:20',
            'jam_buka' => 'nullable|array',
            'harga_min' => 'nullable|numeric|min:0',
            'harga_max' => 'nullable|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'fasilitas' => 'nullable|array',
            'status' => 'required|in:aktif,tutup,review',
            'foto_utama' => 'nullable|image|max:5120',
            'kode_dispar' => 'nullable|string|max:20|unique:tempat',
            'tgl_daftar_dispar' => 'nullable|date',
        ]);

        if ($request->hasFile('foto_utama')) {
            $validated['foto_utama'] = $request->file('foto_utama')->store('tempat', 'public');
        }

        $validated['sumber_dispar'] = $request->boolean('sumber_dispar');

        Tempat::create($validated);

        return redirect()->route('admin.tempat.index')
            ->with('success', 'Tempat berhasil ditambahkan!');
    }

    public function edit(string $id)
    {
        $tempat = Tempat::with([
            'kategori',
            'kecamatan',
            'ulasan' => fn ($q) => $q->with(['user', 'analisisSentimen'])->latest(),
        ])->findOrFail($id);
        $kategoriList = Kategori::all();
        $kecamatanList = Kecamatan::orderBy('nama')->get();

        return view('admin.tempat.edit', compact('tempat', 'kategoriList', 'kecamatanList'));
    }

    public function update(Request $request, string $id)
    {
        $tempat = Tempat::findOrFail($id);

        $validated = $request->validate([
            'nama_usaha' => 'required|string|max:200',
            'kategori_id' => 'required|exists:kategori,id',
            'kecamatan_id' => 'required|exists:kecamatan,id',
            'alamat' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'no_telepon' => 'nullable|string|max:20',
            'jam_buka' => 'nullable|array',
            'harga_min' => 'nullable|numeric|min:0',
            'harga_max' => 'nullable|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'fasilitas' => 'nullable|array',
            'status' => 'required|in:aktif,tutup,review',
            'foto_utama' => 'nullable|image|max:5120',
            'kode_dispar' => 'nullable|string|max:20|unique:tempat,kode_dispar,'.$tempat->id,
            'tgl_daftar_dispar' => 'nullable|date',
        ]);

        if ($request->hasFile('foto_utama')) {
            if ($tempat->foto_utama) {
                Storage::disk('public')->delete($tempat->foto_utama);
            }
            $validated['foto_utama'] = $request->file('foto_utama')->store('tempat', 'public');
        }

        $validated['sumber_dispar'] = $request->boolean('sumber_dispar');
        $tempat->update($validated);

        return redirect()->route('admin.tempat.index')
            ->with('success', 'Tempat berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $tempat = Tempat::findOrFail($id);
        if ($tempat->foto_utama) {
            Storage::disk('public')->delete($tempat->foto_utama);
        }
        $tempat->delete();

        return redirect()->route('admin.tempat.index')
            ->with('success', 'Tempat berhasil dihapus.');
    }

    public function updateUlasan(Request $request, string $tempatId, string $ulasanId)
    {
        $ulasan = Ulasan::where('tempat_id', $tempatId)->findOrFail($ulasanId);

        $validated = $request->validate([
            'rating' => 'required|numeric|min:1|max:5',
            'teks_ulasan' => 'required|string|min:10|max:2000',
        ]);

        UlasanModerationLog::create([
            'ulasan_id' => $ulasan->id,
            'tempat_id' => $ulasan->tempat_id,
            'admin_id' => auth()->id(),
            'action' => 'updated',
            'old_rating' => $ulasan->rating,
            'new_rating' => $validated['rating'],
            'old_text' => $ulasan->teks_ulasan,
            'new_text' => $validated['teks_ulasan'],
        ]);

        $ulasan->update($validated);
        AnalyzeSentimentJob::dispatch($ulasan->fresh());

        return back()->with('success', 'Ulasan pengguna berhasil diperbarui.');
    }

    public function destroyUlasan(string $tempatId, string $ulasanId)
    {
        $ulasan = Ulasan::where('tempat_id', $tempatId)->findOrFail($ulasanId);

        UlasanModerationLog::create([
            'ulasan_id' => $ulasan->id,
            'tempat_id' => $ulasan->tempat_id,
            'admin_id' => auth()->id(),
            'action' => 'deleted',
            'old_rating' => $ulasan->rating,
            'old_text' => $ulasan->teks_ulasan,
        ]);

        $ulasan->analisisSentimen()->delete();
        $ulasan->delete();

        return back()->with('success', 'Ulasan pengguna berhasil dihapus.');
    }

    public function importCsv(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:10240',
        ]);

        $csv = Reader::createFromPath($request->file('csv_file')->getPathname(), 'r');
        $csv->setHeaderOffset(0);

        $imported = 0;
        $errors = [];

        foreach ($csv->getRecords() as $index => $record) {
            try {
                $kategori = Kategori::where('jenis', strtolower($record['jenis_kategori'] ?? ''))->first();
                $kecamatan = Kecamatan::where('nama', $record['kecamatan'] ?? '')->first();

                if (! $kategori || ! $kecamatan) {
                    $errors[] = 'Baris '.($index + 2).': Kategori atau kecamatan tidak ditemukan';

                    continue;
                }

                $kodeDispar = trim($record['kode_dispar'] ?? '');
                $payload = [
                    'kategori_id' => $kategori->id,
                    'kecamatan_id' => $kecamatan->id,
                    'nama_usaha' => $record['nama_usaha'] ?? '',
                    'alamat' => $record['alamat'] ?? '',
                    'latitude' => $record['latitude'] ?? null,
                    'longitude' => $record['longitude'] ?? null,
                    'no_telepon' => $record['no_telepon'] ?? null,
                    'harga_min' => $record['harga_min'] ?? null,
                    'harga_max' => $record['harga_max'] ?? null,
                    'deskripsi' => $record['deskripsi'] ?? null,
                    'status' => 'review',
                    'sumber_dispar' => true,
                    'kode_dispar' => $kodeDispar !== '' ? $kodeDispar : null,
                    'tgl_daftar_dispar' => $record['tgl_daftar'] ?? now()->toDateString(),
                ];

                if ($kodeDispar !== '') {
                    Tempat::updateOrCreate(['kode_dispar' => $kodeDispar], $payload);
                } else {
                    Tempat::create($payload);
                }

                $imported++;
            } catch (\Exception $e) {
                $errors[] = 'Baris '.($index + 2).': '.$e->getMessage();
            }
        }

        $message = "Berhasil import {$imported} data.";
        if (! empty($errors)) {
            $message .= ' '.count($errors).' baris error.';
        }

        return back()->with('success', $message)->with('import_errors', $errors);
    }
}
