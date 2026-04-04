<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KategoriLaporan;
use App\Models\Laporan;
use App\Models\Produk;
use App\Models\Kios;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = Laporan::with(['user', 'kategori', 'reportable']);

        if (Auth::user()->hasRole('user')) {
            $query->where('user_id', Auth::id());
        }
        // admin sees all

        $laporans = $query->latest()->paginate(10);

        return response()->json($laporans);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'reportable_type' => 'required|in:App\\Models\\Produk,App\\Models\\Kios,App\\Models\\Driver',
            'reportable_id' => 'required|integer|exists:produks,id|exists:kios,id|exists:drivers,id', // adjust table names if needed
            'kategori_laporan_id' => 'required|exists:kategori_laporans,id',
            'alasan' => 'required|string|max:1000'
        ]);

        // Validate kategori matches type and active
        $kategori = KategoriLaporan::findOrFail($validated['kategori_laporan_id']);
        if ($kategori->reportable_type !== $validated['reportable_type'] || !$kategori->is_active) {
            return response()->json(['error' => 'Kategori tidak valid untuk jenis laporan ini.'], 422);
        }

        // Check reportable exists for type
        $reportableExists = match($validated['reportable_type']) {
            'App\\Models\\Produk' => Produk::find($validated['reportable_id']),
            'App\\Models\\Kios' => Kios::find($validated['reportable_id']),
            'App\\Models\\Driver' => Driver::find($validated['reportable_id']),
            default => null
        };

        if (!$reportableExists) {
            return response()->json(['error' => 'Target laporan tidak ditemukan.'], 422);
        }

        $laporan = Laporan::create([
            'user_id' => Auth::id(),
            'reportable_type' => $validated['reportable_type'],
            'reportable_id' => $validated['reportable_id'],
            'kategori_laporan_id' => $validated['kategori_laporan_id'],
            'alasan' => $validated['alasan'],
            'status' => 'pending'
        ]);

        return response()->json([
            'message' => 'Laporan berhasil dikirim.',
            'data' => $laporan->load(['user', 'kategori', 'reportable'])
        ], 201);
    }
}

