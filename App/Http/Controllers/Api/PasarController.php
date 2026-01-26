<?php

namespace App\Http\Controllers\Api;

use App\Models\Pasar;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
            'foto_pasar' => 'nullable|string',
        ],
        
        [
            'nama_pasar.required' => 'Nama pasar wajib diisi',
            'alamat.required' => 'Alamat wajib diisi',
            'deskripsi.required' => 'Deskripsi wajib diisi',
            'kontak.required' => 'Kontak wajib diisi',
            'longitude.required' => 'Longitude wajib diisi',
            'latitude.required' => 'Latitude wajib diisi',
            'ongkir.required' => 'Ongkir wajib diisi',
        ]);

        $pasar->update($request->only(

        [
            'nama_pasar' => $request->nama_pasar,
            'alamat' => $request->alamat,
            'deskripsi' => $request->deskripsi,
            'kontak' => $request->kontak,
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
            'ongkir' => $request->ongkir,
            'foto_pasar' => $request->foto_pasar,
        ]));
        
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
