<?php

namespace App\Http\Controllers;

use App\Models\Kios;
use App\Models\Pasar;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index()
    {
        // Statistik untuk carousel cards
        $totalPasar = Pasar::count();
        $totalUser = User::count();
        $totalKios = Kios::count();
        $totalPemilikKios = User::where('role', 'pemilik_kios')->count();
        $totalAdmin = User::where('role', 'admin')->count();
        $totalPelanggan = User::where('role', 'user')->count();

        // Data untuk grafik - Pasar per bulan (contoh)
        $pasarPerBulan = Pasar::selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
            ->whereYear('created_at', date('Y'))
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        // Data untuk grafik - Kios per bulan
        $kiosPerBulan = Kios::selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
            ->whereYear('created_at', date('Y'))
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        
        // User terbaru
        $userTerbaru = User::with('kios')
            ->where('role', 'pemilik_kios')
            ->latest()
            ->take(5)
            ->get();

        // Kios terbaru
        $kiosTerbaru = Kios::with(['pasar', 'user'])
            ->latest()
            ->take(5)
            ->get();

        // Distribusi user berdasarkan role
        $userByRole = User::selectRaw('role, COUNT(*) as total')
            ->groupBy('role')
            ->get();

        return view('admin.index', compact(
            'totalPasar',
            'totalUser',
            'totalKios',
            'totalPemilikKios',
            'totalAdmin',
            'totalPelanggan',
            'pasarPerBulan',
            'kiosPerBulan',
            'userTerbaru',
            'kiosTerbaru',
            'userByRole'
        ));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
