<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Kios;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApiProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
       public function index(Request $request)
{
    $query = Produk::query();

    if ($request->kategori_id) {
        $query->where('kategori_id', $request->kategori_id);
    }

    if ($request->search) {
        $query->where('nama_produk', 'like', '%' . $request->search . '%');
    }

    $produk = $query->latest()->get();

    

    return response()->json([
        'status' => 'success',
        'message' => 'Semua data produk berhasil ditampilkan',
        'data' => $produk
    ], 200);
}

    /**
     * Show the form for creating a new resource.
     */
    

    /**
     * Store a newly created resource in storage.
     */
   public function myProduk($id)
{
    $produk = Produk::find($id);

    if (!$produk) {
        return response()->json(['message' => 'Produk tidak ditemukan'], 404);
    }

    $userId = auth()->id();
    $kios = Kios::where('user_id', $userId)->first();

    if (!$kios) {
        return response()->json(['message' => 'Kios tidak ditemukan'], 404);
    }

    if ($produk->kios_id !== $kios->id) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    return response()->json($produk, 200);
}
    public function store(Request $request)
    {
        $request->validate([
            'nama_produk'   => 'required|string|max:255',
            'kategori_id'   => 'required|exists:kategoris,id',
            'harga'         => 'required|numeric|min:0',
            'stok'          => 'required|integer|min:0',
            'berat_satuan'  => 'required|string',
            'deskripsi'     => 'nullable|string',
            'foto'   => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ], [
            'nama_produk.required' => 'Nama produk wajib diisi.',
            'kategori_id.required' => 'Kategori wajib dipilih.',
            'harga.required'       => 'Harga wajib diisi.',
            'berat_satuan.required' => 'Berat satuan wajib diisi.',
            'stok.required'        => 'Stok wajib diisi.',
            'foto.max'              => 'Ukuran foto maksimal 2MB.',
        ]);

        $data = $request->only([
            'nama_produk',
            'kategori_id',
            'harga',
            'berat_satuan',
            'stok',
            'deskripsi'
        ]);

        $kios = Kios::where('user_id', auth()->id())->first();
        if (!$kios) {
    return response()->json([
        'status' => 'error',
        'message' => 'User belum memiliki kios'
    ], 404);    
        }
        $data['kios_id'] = $kios->id;

        if ($request->hasFile('foto')) {
                $tanggal = Carbon::now()->format('dmY');

                $urutan = Produk::whereDate('created_at', Carbon::today())->count() + 1;

                $ext = $request->file('foto')->getClientOriginalExtension();

                $namaFile = $tanggal . '_' . $urutan . '.' . $ext;

                $data['foto'] = $request->file('foto')
                    ->storeAs('foto_produk', $namaFile, 'public');
            }


        Produk::create($data);

       return response()->json([
            'status' => 'success',
            'message' => 'Produk berhasil ditambahkan',
            'data' => $data
        ], 201);
    }

    /**
     * Show the form for editing the specified resource.
     */
   

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Produk $produk)
    {
        $request->validate([
            'nama_produk'   => 'required|string|max:255',
            'kategori_id'   => 'required|exists:kategoris,id',
            'kios_id'       => 'required|exists:kios,id',
            'harga'         => 'required|numeric|min:0',
            'stok'          => 'required|integer|min:0',
            'berat_satuan'  => 'required|numeric|min:0',
            'deskripsi'     => 'nullable|string',
            'foto'   => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ],
        [
            'nama_produk.required' => 'Nama produk wajib diisi.',
            'kategori_id.required' => 'Kategori wajib dipilih.',
            'kios_id.required'     => 'Kios wajib dipilih.',
            'harga.required'       => 'Harga wajib diisi.',
            'stok.required'        => 'Stok wajib diisi.',
            'berat_satuan.required' => 'Berat satuan wajib diisi.',
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

                if ($produk->foto && Storage::disk('public')->exists($produk->foto)) {
                    Storage::disk('public')->delete($produk->foto);
                }

                $tanggal = Carbon::now()->format('dmY');
                $urutan = Produk::whereDate('created_at', Carbon::today())->count() + 1;
                $ext = $request->file('foto')->getClientOriginalExtension();

                $namaFile = $tanggal . '_' . $urutan . '.' . $ext;

                $data['foto'] = $request->file('foto')
                    ->storeAs('foto_produk', $namaFile, 'public');
        }


        $produk->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Produk berhasil diperbarui',
            'data' => $data
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);

        if ($produk->foto && Storage::disk('public')->exists($produk->foto)) {
            Storage::disk('public')->delete($produk->foto);
        }

        $produk->delete();

       return response()->json([
            'status' => 'success',
            'message' => 'Produk berhasil dihapus',
        ], 200);
    }

   
}
