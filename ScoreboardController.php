<?php

namespace App\Http\Controllers;

use App\Models\KeputusanAcara;
use App\Models\Rumah_Sukan;

class ScoreboardController extends Controller
{
    public function index()
    {
        return view('esutkv.scoreboard.index', $this->scoreboardData());
    }

    public function data()
    {
        return response()->json($this->scoreboardData());
    }

    private function scoreboardData(): array
    {
        $houses = Rumah_Sukan::withSum(
            ['keputusanAcara as jumlah_mata' => fn ($query) => $query->where('status', 'approved')],
            'mata'
        )
            ->orderByDesc('jumlah_mata')
            ->get()
            ->map(fn (Rumah_Sukan $rumah) => [
                'nama' => $rumah->nama_rumah,
                'warna' => $rumah->warna,
                'mata' => (int) ($rumah->jumlah_mata ?? 0),
            ])
            ->values();

        $results = KeputusanAcara::with(['acara', 'peserta', 'rumahSukan'])
            ->where('status', 'approved')
            ->latest()
            ->limit(10)
            ->get()
            ->map(fn (KeputusanAcara $keputusan) => [
                'acara' => $keputusan->acara->nama_acara,
                'peserta' => $keputusan->peserta->nama_peserta,
                'rumah' => $keputusan->rumahSukan->nama_rumah,
                'pingat' => $keputusan->pingat,
                'mata' => $keputusan->mata,
            ])
            ->values();

        return [
            'rumahSukans' => $houses,
            'keputusans' => $results,
            'dikemasKini' => now()->format('H:i:s'),
        ];
    }
}
