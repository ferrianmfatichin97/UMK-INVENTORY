<?php

namespace App\Http\Controllers;

use App\Models\PengadaanBarang;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PengadaanDashboardController extends Controller
{
    public function index()
    {
        $pengadaans = PengadaanBarang::with('details')
            ->orderByRaw("
                CASE
                    WHEN status = 'diproses' THEN 1
                    WHEN status = 'ditolak' THEN 2
                    WHEN status = 'selesai' THEN 3
                    ELSE 4
                END
            ")
            ->latest('tanggal_dibutuhkan') 
            ->paginate(10);

        $stats = [
            'total'     => PengadaanBarang::count(),
            'diproses'  => PengadaanBarang::where('status', 'diproses')->count(),
            'selesai'   => PengadaanBarang::where('status', 'selesai')->count(),
            'ditolak'   => PengadaanBarang::where('status', 'ditolak')->count(),
        ];

        return view('pengadaan_dashboard', compact('pengadaans', 'stats'));
    }
}
