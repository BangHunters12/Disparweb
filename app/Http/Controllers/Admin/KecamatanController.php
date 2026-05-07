<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kecamatan;
use Illuminate\Http\Request;

class KecamatanController extends Controller
{
    public function index()
    {
        $kecamatan = Kecamatan::withCount('restoran')->orderBy('nama')->paginate(25);
        return view('admin.kecamatan.index', compact('kecamatan'));
    }

    public function create()
    {
        return view('admin.kecamatan.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'       => 'required|string|max:100|unique:kecamatan,nama',
            'kode_pos'   => 'nullable|string|max:10',
            'lat_center' => 'nullable|numeric',
            'lng_center' => 'nullable|numeric',
        ], ['nama.unique' => 'Nama kecamatan sudah ada.']);

        Kecamatan::create($request->only(['nama', 'kode_pos', 'lat_center', 'lng_center']));
        return redirect()->route('admin.kecamatan.index')->with('success', 'Kecamatan berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $kecamatan = Kecamatan::findOrFail($id);
        return view('admin.kecamatan.form', compact('kecamatan'));
    }

    public function update(Request $request, string $id)
    {
        $kecamatan = Kecamatan::findOrFail($id);
        $request->validate([
            'nama'     => 'required|string|max:100|unique:kecamatan,nama,' . $id,
            'kode_pos' => 'nullable|string|max:10',
        ]);
        $kecamatan->update($request->only(['nama', 'kode_pos', 'lat_center', 'lng_center']));
        return redirect()->route('admin.kecamatan.index')->with('success', 'Kecamatan berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $kecamatan = Kecamatan::findOrFail($id);
        if ($kecamatan->restoran()->count() > 0) {
            return back()->withErrors(['error' => 'Kecamatan tidak bisa dihapus karena masih memiliki restoran.']);
        }
        $kecamatan->delete();
        return redirect()->route('admin.kecamatan.index')->with('success', 'Kecamatan berhasil dihapus.');
    }
}
