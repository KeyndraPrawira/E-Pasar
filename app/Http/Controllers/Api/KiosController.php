<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Kios;
use App\Models\Pasar;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Test\Constraint\ResponseFormatSame;

class KiosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $user = auth()->user();

    if ($user->role == 'pedagang') {
        $kios = Kios::where('user_id', $user->id)->get();
    } else {
        $kios = Kios::all();
    }

    return response()->json($kios, 200);
}

    public function myKios()
{
    $user = auth()->user();

    $kios = Kios::where('user_id', $user->id)->get();

    return response()->json($kios, 200);
}


    
   

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        if ($user->role !== 'pedagang') {
            return response()->json([
                "message" => "Anda bukan pedagang"
            ], 403);
        }

        if (Kios::where('user_id', $user->id)->exists()) {
            return response()->json([
                'message' => 'Kamu sudah punya kios'
            ], 400);
        }

        $request->validate([
            'nama_kios' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'foto_kios' => 'nullable|image|max:2048',
            'deskripsi' => 'nullable|string',
            'jam_buka' => 'required|date_format:H:i',
            'jam_tutup' => 'required|date_format:H:i',
        ]);

        $pasar = Pasar::first();

        $data = $request->only([
            'nama_kios',
            'lokasi',
            'deskripsi',
            'jam_buka',
            'jam_tutup'
        ]);

        $data['user_id'] = $user->id;
        $data['pasar_id'] = $pasar->id;

        if ($request->hasFile('foto_kios')) {
            $data['foto_kios'] = $request->file('foto_kios')
                ->store('foto_kios', 'public');
        }

        $kios = Kios::create($data);

        return response()->json([
            "kios" => $kios,
            "status" => 201,
            "message" => "Kios Berhasil Ditambahkan"
        ], 201);
    }


    /**
     * Display the specified resource.
     */
    

    /**
     * Show the form for editing the specified resource.
     */
    public function updateStatus(Kios $kios, Request $request)
    {
        if ($kios->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'Tidak diizinkan'
            ], 403);
        }
        
            $request->validate([
                'status' => 'required|in:buka,tutup',
            ], [
                'status.required' => 'Status kios wajib diisi',
                'status.in' => 'Status kios harus berupa buka atau tutup',
            ]);



        $kios->status = $request->status;
        $kios->save();

        return response()->json([
            'message' => 'Status kios berhasil diubah',
            'data' => $kios,
            'status' => 200
        ]);
    }

    

    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kios $kios)
    {
       
        if ($kios->user_id != auth()->id()) {
            return response()->json([
                'message' => 'Tidak diizinkan, id anda :'.auth()->id().'. id yang diperlukan :'. $kios->user_id

            ], 403);
        }

        $request->validate([
            'nama_kios' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'foto_kios' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'deskripsi' => 'nullable|string',
            'jam_buka' => 'required|date_format:H:i',
            'jam_tutup' => 'required|date_format:H:i'
        ],
        [
            'nama_kios.required' => 'Nama Kios wajib diisi.',
            'lokasi.required' => 'Alamat Kios wajib diisi.',
            'foto_kios.max' => 'Ukuran foto maksimal 2MB.',
            'jam_buka.required' => 'Isi jam buka terlebih dahulu',
            'jam_tutup.required' => 'Isi jam tutup terlebih dahulu',
            'jam_buka.date_format' => 'Tipe input jam buka tidak valid',
            'jam_tutup.date_format' => 'Tipe jam tutup tidak valid'
        ]);

        $data = $request->only(['nama_kios', 'lokasi',  'kontak', 'deskripsi', 'jam_buka', 'jam_tutup']);

        if($request->hasFile('foto_kios')){
            if($kios->foto && Storage::disk('public')->exists($kios->foto)){
                Storage::disk('public')->delete($kios->foto);
            }
            $data['foto_kios'] = $request->file('foto_kios')->store('foto_kios', 'public');
        }

        $kios->update($data);

        return response()->json(
            [
                'message' => 'kios berhasil diedit',
                'data' => $kios,
                'status' => 200
            ]
        );

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id, Kios $kios)
    {
        if ($kios->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'Tidak diizinkan'
            ], 403);
        }

        $kios = Kios::findOrFail($id);
        if($kios->foto && Storage::disk('public')->exists($kios->foto)){
            Storage::disk('public')->delete($kios->foto);
        }
        $kios->delete();
        return response()->json(
            [
                'message' => 'kios berhasil dihapus',
                'data' => $kios,
                'status' => 200
            ]
        );
    }
}
