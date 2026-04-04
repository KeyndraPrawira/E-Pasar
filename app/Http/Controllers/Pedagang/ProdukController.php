<?php

namespace App\Http\Controllers\Pedagang;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $produk = Produk::where('pedagang_id', auth()->id())->get();
        return view('pedagang.produk.index', compact('produk'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategori = Kategori::all();
        return view('pedagang.produk.create');
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
    {
        $request->validate([
            'nama_produk'   => 'required|string|max:255',
            'kategori_id'   => 'required|exists:kategoris,id',
            'kios_id'       => 'required|exists:kios,id',
            'harga'         => 'required|numeric|min:0',
            'stok'          => 'required|integer|min:0',
            'berat_satuan'  => 'required|string',
            'deskripsi'     => 'nullable|string',
            'foto'   => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ], [
            'nama_produk.required' => 'Nama produk wajib diisi.',
            'kategori_id.required' => 'Kategori wajib dipilih.',
            'kios_id.required'     => 'Kios wajib dipilih.',
            'harga.required'       => 'Harga wajib diisi.',
            'berat_satuan.required' => 'Berat satuan wajib diisi.',
            'stok.required'        => 'Stok wajib diisi.',
            'foto.max'              => 'Ukuran foto maksimal 2MB.',
        ]);

        $data = $request->only([
            'nama_produk',
            'kategori_id',
            'kios_id',
            'harga',
            'berat_satuan',
            'stok',
            'deskripsi'
        ]);

        if ($request->hasFile('foto')) {
                $tanggal = Carbon::now()->format('dmY');

                $urutan = Produk::whereDate('created_at', Carbon::today())->count() + 1;

                $ext = $request->file('foto')->getClientOriginalExtension();

                $namaFile = $tanggal . '_' . $urutan . '.' . $ext;

                $data['foto'] = $request->file('foto')
                    ->storeAs('foto_produk', $namaFile, 'public');
            }


        Produk::create($data);

                toast('Produk berhasil disimpan', 'success');
        return redirect()
            ->route('produks.index')
            ->with('success', 'Produk berhasil ditambahkan');
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
