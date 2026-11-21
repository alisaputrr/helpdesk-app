<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Query dasar: hanya tiket yang belum masuk tempat sampah.
        $base = fn () => DB::table('tickets')->whereNull('deleted_at');
        $today = now()->toDateString();

        $total = $base()->count();

        // Rekap status: satu baris per status (COUNT + GROUP BY).
        $counts = $base()
            ->select('status')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $statusList = ['Baru', 'Diproses', 'Menunggu', 'Selesai'];
        $byStatus = collect($statusList)->map(fn ($s) => [
            'status' => $s,
            'total' => (int) ($counts[$s] ?? 0),
            'persen' => $total ? round(($counts[$s] ?? 0) / $total * 100) : 0,
        ]);

        $baru = (int) ($counts['Baru'] ?? 0);
        $diproses = (int) ($counts['Diproses'] ?? 0);
        $selesai = (int) ($counts['Selesai'] ?? 0);

        $tinggi = $base()
            ->where('prioritas', 'Tinggi')
            ->where('status', '!=', 'Selesai')
            ->count();

        $terlambat = $base()
            ->whereNotNull('target_selesai')
            ->whereDate('target_selesai', '<', $today)
            ->where('status', '!=', 'Selesai')
            ->count();

        // Tinggi ATAU terlambat dihitung sekali (pakai closure supaya OR tidak bocor).
        $perhatian = $base()
            ->where('status', '!=', 'Selesai')
            ->where(function ($q) use ($today) {
                $q->where('prioritas', 'Tinggi')
                  ->orWhere(function ($q2) use ($today) {
                      $q2->whereNotNull('target_selesai')
                         ->whereDate('target_selesai', '<', $today);
                  });
            })
            ->count();

        $recent = $base()->orderByDesc('created_at')->limit(6)->get();

        return view('dashboard', [
            'total' => $total,
            'perluDitangani' => $baru + $diproses,
            'baru' => $baru,
            'diproses' => $diproses,
            'selesai' => $selesai,
            'rate' => $total ? round($selesai / $total * 100) : 0,
            'perhatian' => $perhatian,
            'tinggi' => $tinggi,
            'terlambat' => $terlambat,
            'byStatus' => $byStatus,
            'recent' => $recent,
        ]);
    }
}
