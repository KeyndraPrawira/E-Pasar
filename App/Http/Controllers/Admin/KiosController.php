<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Kios;
use App\Models\Pasar;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        return view('admin.kios.index', compact('kios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pasar = Pasar::first();
        $penjual = User::where('role', 'pedagang')->get();
        return view('admin.kios.create', compact('pasar', 'penjual'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kios' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id|unique:kios,user_id',
            'pasar_id' => 'required|exists:pasars,id', 
            'foto_kios' => 'nullable|max:2048',
            'kontak' => 'nullable|string|max:100',
            'deskripsi' => 'nullable|string',
        ],
        [
            'nama_kios.required' => 'Nama Kios wajib diisi.',
            'lokasi.required' => 'Alamat Kios wajib diisi.',
            'user_id.required' => 'Pemilik Kios wajib diisi.',
            'user_id.exists' => 'Pemilik Kios tidak valid.',
            'user_id.unique' => 'Pedagang ini sudah memiliki kios.',
            'pasar_id.required' => 'Pasar wajib diisi.',
            'foto_kios.max' => 'Ukuran foto maksimal 2MB.',
            'pasar_id.exists' => 'Pasar tidak valid.',
        ]);



        
        $data = $request->only(['nama_kios', 'lokasi', 'user_id', 'pasar_id', 'kontak', 'deskripsi']);


        if($request->hasFile('foto_kios')){
        $data['foto_kios'] = $request->file('foto_kios')->store('foto_kios', 'public');
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
        $pasar = Pasar::first();
        $pedagang = User::where('role', 'pedagang')->get();
        
        $selectedPedagangid = $kios->user_id;
        return view('admin.kios.edit', data: compact('kios', 'pasar', 'pedagang', 'selectedPedagangid'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kios $kios)
    {
        $request->validate([
            'nama_kios' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'foto_kios' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
            'kontak' => 'nullable|string|max:100',
            'deskripsi' => 'nullable|string',
        ],
        [
            'nama_kios.required' => 'Nama Kios wajib diisi.',
            'lokasi.required' => 'Alamat Kios wajib diisi.',
            'foto_kios.mimes' => 'Format foto harus berupa jpeg, png, jpg, atau svg.',
            'user_id.required' => 'Pemilik Kios wajib diisi.',
            'user_id.exists' => 'Pemilik Kios tidak valid.',
            'foto_kios.max' => 'Ukuran foto maksimal 2MB.',
        ]);

        $data = $request->only(['nama_kios', 'lokasi', 'user_id',  'kontak', 'deskripsi']);

        if($request->hasFile('foto_kios')){
            if($kios->foto && Storage::disk('public')->exists($kios->foto)){
                Storage::disk('public')->delete($kios->foto);
            }
            $data['foto_kios'] = $request->file('foto_kios')->store('foto_kios', 'public');
        }

        $kios->update($data);

        return redirect()->route('kios.index')->with('success', 'Kios Berhasil Diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $kios = Kios::findOrFail($id);
        if($kios->foto && Storage::disk('public')->exists($kios->foto)){
            Storage::disk('public')->delete($kios->foto);
        }
        $kios->delete();
        return redirect()->route('admin.kios.index')->with('success', 'Kios Berhasil Dihapus');
    }
}
