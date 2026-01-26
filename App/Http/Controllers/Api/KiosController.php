<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Kios;
use App\Models\Pasar;
use App\Models\User;
use Illuminate\Http\Request;

class KiosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kios = Kios::all();
        $data = [
            "kios" => $kios,
            "status" => 200,
            "message" => "Data Kios Berhasil Ditampilkan"
        ];
        return view('kios.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pasar = Pasar::all();
        $penjual = User::where('role', 'penjual')->get();
        return view('kios.create', compact('pasar', 'penjual'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kios' => 'required|string|max:255',
            'alamat_kios' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'pasar_id' => 'required|exists:pasars,id', 
            'foto_kios' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'kontak' => 'nullable|string|max:100',
            'deskripsi' => 'nullable|string',
        ],
        [
            'nama_kios.required' => 'Nama Kios wajib diisi.',
            'lokasi.required' => 'Alamat Kios wajib diisi.',
            'user_id.required' => 'Pemilik Kios wajib diisi.',
            'user_id.exists' => 'Pemilik Kios tidak valid.',
            'pasar_id.required' => 'Pasar wajib diisi.',
            'foto_kios.max' => 'Ukuran foto maksimal 2MB.',
            'pasar_id.exists' => 'Pasar tidak valid.',
        ]);



        
        $data = $request->only(['nama_kios', 'lokasi', 'user_id', 'pasar_id', 'kontak', 'deskripsi']);

        if($request->hasFile('foto')){
        $data['foto'] = $request->file('foto')->store('foto_kios', 'public');
        }

        Kios::create($data);

        return redirect()->route('kios.index')->with('success', 'Kios Berhasil Ditambahkan');
        

    }

    /**
     * Display the specified resource.
     */
    public function show(Kios $kios)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kios $kios)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kios $kios)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kios $kios)
    {
        //
    }
}
