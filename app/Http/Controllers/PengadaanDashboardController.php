<?php

namespace App\Http\Controllers;

use App\Models\PengadaanBarang;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PengadaanDashboardController extends Controller
{
    public function index()
    {
        $pengadaans = PengadaanBarang::with('details')->get();

        $pengadaans = $pengadaans->sortBy([
            fn ($a, $b) => self::urgensiValue($b->urgensi) <=> self::urgensiValue($a->urgensi),
            fn ($a, $b) => Carbon::parse($a->tanggal_dibutuhkan)->timestamp 
                        <=> Carbon::parse($b->tanggal_dibutuhkan)->timestamp
        ]);

        $pengadaans = $pengadaans->take(10);

        $stats = [
            'total'     => PengadaanBarang::count(),
            'diproses'  => PengadaanBarang::where('status', 'diproses')->count(),
            'selesai'   => PengadaanBarang::where('status', 'selesai')->count(),
            'ditolak'   => PengadaanBarang::where('status', 'ditolak')->count(),
        ];

        return view('pengadaan_dashboard', compact('pengadaans', 'stats'));
    }

    private static function urgensiValue(string $urgensi): int
    {
        return match (strtolower($urgensi)) {
            'tinggi' => 3,
            'sedang' => 2,
            'rendah' => 1,
            default => 0,
        };
    }
}
