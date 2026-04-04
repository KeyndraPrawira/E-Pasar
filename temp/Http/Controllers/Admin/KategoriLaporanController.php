<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriLaporan;
use Illuminate\Http\Request;

class KategoriLaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = KategoriLaporan::query();

        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

$kategoriLaporans = $query->get();
        return view('admin.kategori-laporan.index', compact('kategoriLaporans'));
    }

    public function create()
    {
        $reportableTypes = [
            'App\\Models\\Produk' => 'Produk',
            'App\\Models\\Kios' => 'Kios',
            'App\\Models\\Driver' => 'Driver'
        ];
        return view('admin.kategori-laporan.create', compact('reportableTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'reportable_type' => 'required|in:App\\\\Models\\\\Produk,App\\\\Models\\\\Kios,App\\\\Models\\\\Driver',
            'is_active' => 'boolean'
        ],
        [
            'reportable_type.in' => 'Jenis laporan tidak valid. Pilih salah satu: Produk, Kios, Driver.'
        ]);

        KategoriLaporan::create($validated);

        return redirect()->route('kategori-laporan.index')->with('success', 'Kategori laporan berhasil ditambahkan.');
    }

    public function show(KategoriLaporan $kategoriLaporan)
    {
        return view('admin.kategori-laporan.show', compact('kategoriLaporan'));
    }

    public function edit(KategoriLaporan $kategoriLaporan)
    {
        $reportableTypes = [
            'App\\Models\\Produk' => 'Produk',
            'App\\Models\\Kios' => 'Kios',
            'App\\Models\\Driver' => 'Driver'
        ];
        return view('admin.kategori-laporan.edit', compact('kategoriLaporan', 'reportableTypes'));
    }

    public function update(Request $request, KategoriLaporan $kategoriLaporan)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'reportable_type' => 'required|in:App\\\\Models\\\\Produk,App\\\\Models\\\\Kios,App\\\\Models\\\\Driver',
            'is_active' => 'boolean'
        ]);

        $kategoriLaporan->update($validated);

        return redirect()->route('kategori-laporan.index')->with('success', 'Kategori laporan berhasil diupdate.');
    }

    public function destroy(KategoriLaporan $kategoriLaporan)
    {
        $kategoriLaporan->delete();
        return redirect()->route('kategori-laporan.index')->with('success', 'Kategori laporan berhasil dihapus.');
    }
}

