<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index(){
        $kategori = Kategori::all();
        return response()->json(
            [
                'message' => 'menampilkan semua data kategori',
                'data' => $kategori,
                'status' => 200
            ]
        );
    }
}
