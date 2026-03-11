<?php

namespace App\Http\Controllers\Api;

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
        
       return response()->json([
            'status' => 'success',
            'message' => 'Data pasar berhasil ditampilkan',
            'data' => $pasar,
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    

    /**
     * Display the specified resource.
     */
    
    

    /**
     * Show the form for editing the specified resource.
     */
   
}
