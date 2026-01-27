<?php

namespace App\Http\Controllers\Admin;

use App\Models\Pasar;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PasarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pasar = Pasar::first();
        $data = [
            'pasar' => $pasar,
            'status' => 'success',
            'message' => 'Data pasar berhasil ditampilkan',

        ]; 
       return view('admin.pasar.index', compact('pasar'));
    }

    /**
     * Show the form for creating a new resource.
     */
    

    /**
     * Display the specified resource.
     */
    public function show(Pasar $pasar)
    {
        //
    }

    public function edit($id)
    {
        $pasar = Pasar::findOrFail($id);
        return view('admin.pasar.edit', compact('pasar'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function update(Pasar $pasar, Request $request)

    {
        $request->validate([
           
            'nama_pasar' => 'required',
            'alamat' => 'required',
            'deskripsi' => 'required|string',
            'kontak' => 'required|string',
            'longitude' => 'required',
            'latitude' => 'required',
            'ongkir' => 'required|numeric|min:0',
            'foto_pasar' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ],
        
        [
            'nama_pasar.required' => 'Nama pasar wajib diisi',
            'alamat.required' => 'Alamat wajib diisi',
            'deskripsi.required' => 'Deskripsi wajib diisi',
            'kontak.required' => 'Kontak wajib diisi',
            'longitude.required' => 'Longitude wajib diisi',
            'foto_pasar.image' => 'tolong masukkan gambar valid',
            'latitude.required' => 'Latitude wajib diisi',
            'ongkir.required' => 'Ongkir wajib diisi',
        ]);

       $data = $request->only(
        [
            'nama_pasar',
            'alamat',
            'deskripsi',
            'kontak',
            'longitude',
            'latitude',
            'ongkir',   

        ]);
        if($request->hasFile('foto_pasar')){
            if($pasar->foto && Storage::disk('public')->exists($pasar->foto)){
                Storage::disk('public')->delete($pasar->foto);
            }
            $data['foto_pasar'] = $request->file('foto_pasar')->store('foto_pasar', 'public');
        }

        $pasar->update($data);
        
        $data = [
            'data' => $request,
            'status' => 200,
            'message' => 'Data pasar berhasil ditampilkan',
        ];
        return redirect()->route('pasar.index')->with('success', 'Data pasar berhasil diperbarui.');
    }

    /**
     * Update the specified resource in storage.
     */
   
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pasar $pasar)
    {
        //
    }
}
