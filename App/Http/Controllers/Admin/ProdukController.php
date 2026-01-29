<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Kios;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

use function Laravel\Prompts\confirm;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $produks = Produk::with(['kategori', 'kios'])->get();
        $title = 'Hapus data!';
        $text = 'Apakah anda yakin ingin menghapus data ini?';

        confirmDelete($title, $text);;
        return view('admin.produk.index', compact('produks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategori = Kategori::all();
        $kios = Kios::all();

        return view('admin.produk.create', compact('kategori', 'kios'));
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
     * Show the form for editing the specified resource.
     */
    public function edit(Produk $produk)
    {
        $kategori = Kategori::all();
        $kios = Kios::all();
       

        return view('admin.produk.edit', compact('produk', 'kategori', 'kios'));
    }

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
        toast('Produk berhasil edit', 'success');

        return redirect()
            ->route('produks.index')
            ->with('success', 'Produk berhasil diperbarui');
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
        toast('Produk berhasil dihapus', 'success');

        return redirect()
            ->route('produks.index')
            ->with('success', 'Produk berhasil dihapus');
    }
}
