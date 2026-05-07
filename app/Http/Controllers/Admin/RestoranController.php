<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ImportLog;
use App\Models\Kecamatan;
use App\Models\Restoran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RestoranController extends Controller
{
    public function index(Request $request)
    {
        $query = Restoran::with(['kecamatan', 'rekomendasiSaw']);

        if ($request->filled('search')) {
            $query->where('nama_usaha', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('kecamatan_id')) {
            $query->where('kecamatan_id', $request->kecamatan_id);
        }
        if ($request->filled('sumber')) {
            $query->where('sumber', $request->sumber);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $restoran      = $query->latest()->paginate(20)->withQueryString();
        $kecamatanList = Kecamatan::orderBy('nama')->get();

        return view('admin.restoran.index', compact('restoran', 'kecamatanList'));
    }

    public function create()
    {
        $kecamatanList = Kecamatan::orderBy('nama')->get();
        return view('admin.restoran.form', compact('kecamatanList'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateRestoran($request);

        if ($request->hasFile('foto_utama')) {
            $validated['foto_utama'] = $request->file('foto_utama')->store('restoran/temp', 'public');
        }

        $restoran = Restoran::create($validated);

        // Move to proper folder
        if (! empty($validated['foto_utama'])) {
            $newPath = "restoran/{$restoran->id}/utama.jpg";
            Storage::disk('public')->move($validated['foto_utama'], $newPath);
            $restoran->update(['foto_utama' => $newPath]);
        }

        ImportLog::create([
            'admin_id'        => Auth::guard('admin')->id(),
            'jenis'           => 'manual',
            'jumlah_berhasil' => 1,
            'jumlah_gagal'    => 0,
        ]);

        return redirect()->route('admin.restoran.index')
            ->with('success', 'Restoran berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $restoran      = Restoran::findOrFail($id);
        $kecamatanList = Kecamatan::orderBy('nama')->get();
        return view('admin.restoran.form', compact('restoran', 'kecamatanList'));
    }

    public function update(Request $request, string $id)
    {
        $restoran  = Restoran::findOrFail($id);
        $validated = $this->validateRestoran($request, $id);

        if ($request->hasFile('foto_utama')) {
            $path = $request->file('foto_utama')->store("restoran/{$id}", 'public');
            $validated['foto_utama'] = $path;
        }

        $restoran->update($validated);
        return redirect()->route('admin.restoran.index')->with('success', 'Restoran berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $restoran = Restoran::findOrFail($id);
        Storage::disk('public')->deleteDirectory("restoran/{$id}");
        $restoran->delete();
        return redirect()->route('admin.restoran.index')->with('success', 'Restoran berhasil dihapus.');
    }

    public function bulkAction(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'action' => 'required|in:activate,deactivate,delete']);
        $ids    = $request->ids;
        $action = $request->action;

        match ($action) {
            'activate'   => Restoran::whereIn('id', $ids)->update(['status' => 'aktif']),
            'deactivate' => Restoran::whereIn('id', $ids)->update(['status' => 'tutup']),
            'delete'     => Restoran::whereIn('id', $ids)->delete(),
        };

        return back()->with('success', 'Bulk action berhasil dijalankan.');
    }

    public function importCsvForm()
    {
        return view('admin.restoran.import-csv');
    }

    public function importCsv(Request $request)
    {
        $request->validate(['file' => 'required|mimes:csv,xlsx,xls|max:5120']);
        return back()->with('success', 'Import CSV berhasil. (Implementasi maatwebsite/excel)');
    }

    public function downloadTemplate()
    {
        $headers  = ['nama_usaha', 'alamat', 'kecamatan', 'no_telepon', 'harga_min', 'harga_max', 'deskripsi', 'kode_dispar'];
        $csvData  = implode(',', $headers) . "\n";
        $csvData .= '"Warung Contoh","Jl. Contoh No. 1, Bondowoso","Bondowoso","08123456789","15000","50000","Deskripsi contoh","BDW-XXX"';

        return response($csvData, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template_import_restoran.csv"',
        ]);
    }

    protected function validateRestoran(Request $request, ?string $exceptId = null): array
    {
        return $request->validate([
            'kecamatan_id' => 'required|exists:kecamatan,id',
            'nama_usaha'   => 'required|string|max:200',
            'alamat'       => 'nullable|string',
            'latitude'     => 'nullable|numeric|between:-90,90',
            'longitude'    => 'nullable|numeric|between:-180,180',
            'no_telepon'   => 'nullable|string|max:20',
            'website'      => 'nullable|url|max:255',
            'harga_min'    => 'nullable|numeric|min:0',
            'harga_max'    => 'nullable|numeric|min:0',
            'deskripsi'    => 'nullable|string',
            'status'       => 'required|in:aktif,tutup,review',
            'foto_utama'   => 'nullable|image|max:4096',
            'fasilitas'    => 'nullable|array',
        ], [
            'kecamatan_id.required' => 'Kecamatan wajib dipilih.',
            'nama_usaha.required'   => 'Nama usaha wajib diisi.',
            'latitude.between'      => 'Koordinat latitude tidak valid.',
            'longitude.between'     => 'Koordinat longitude tidak valid.',
            'foto_utama.image'      => 'File harus berupa gambar.',
            'foto_utama.max'        => 'Ukuran foto maksimal 4MB.',
        ]);
    }
}
