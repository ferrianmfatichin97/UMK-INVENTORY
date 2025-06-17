<?php

namespace App\Http\Controllers;

use App\Models\PengadaanBarang;
use Illuminate\Http\Request;

class PengadaanDashboardController extends Controller
{
    public function index()
    {
        $pengadaans = PengadaanBarang::withCount('details')
            ->latest()
            ->take(10)
            ->get();
        
        

        $stats = [
            'total'     => PengadaanBarang::count(),
            'diproses'  => PengadaanBarang::where('status', 'diproses')->count(),
            'selesai'   => PengadaanBarang::where('status', 'selesai')->count(),
            'ditolak'   => PengadaanBarang::where('status', 'ditolak')->count(),
        ];

        return view('pengadaan_dashboard', compact('pengadaans', 'stats'));
    }
}
