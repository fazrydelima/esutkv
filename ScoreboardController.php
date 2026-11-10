<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\KeputusanAcara;
use App\Models\Rumah_Sukan;


class ScoreboardController extends Controller
{
    // Paparan utama scoreboard
    public function index()
    {
        $rumahSukans = $this->getRumahSukanData();
        $keputusans = $this->getKeputusanTerkini();

        $dikemasKini = now()->format('d/m/Y H:i:s');

        return view('esutkv.scoreboard.index', compact(
            'rumahSukans',
            'keputusans',
            'dikemasKini'
        ));
    }

    // Data JSON untuk JavaScript
    public function data()
    {
        return response()->json([
            'rumahSukans' => $this->getRumahSukanData(),
            'keputusans' => $this->getKeputusanTerkini(),
            'dikemasKini' => now()->format('d/m/Y H:i:s'),
        ]);
    }

    // Jumlah mata setiap rumah sukan
    private function getRumahSukanData()
    {
        return Rumah_Sukan::with('keputusanAcara')->get()
            ->map(function ($rumah) {

                $keputusan = $rumah->keputusanAcara;

                $mataSukantara = $keputusan
                    ->filter(fn($k) =>
                        $k->peserta_id === null &&
                        $k->pingat === null &&
                        $k->jenis_keputusan === null
                    )->sum('mata');

                $mataKejohanan = $keputusan
                    ->filter(fn($k) =>
                        $k->peserta_id !== null &&
                        $k->pingat !== null
                    )->sum('mata');

                $mataRentasDesa = $keputusan
                    ->whereIn('jenis_keputusan', [
                        'terawal',
                        'masa_terhad'
                    ])->sum('mata');

                return [
                    'id' => $rumah->id,
                    'nama' => $rumah->nama_rumah,
                    'warna' => $rumah->warna,
                    'mata' => $mataSukantara
                        + $mataKejohanan
                        + $mataRentasDesa,
                ];
            })
            ->sortByDesc('mata')
            ->values();
    }

    // Keputusan terkini
    private function getKeputusanTerkini()
    {
        return KeputusanAcara::with([
            'acara',
            'peserta',
            'rumahSukan'
        ])
        ->whereNotNull('peserta_id')
        ->whereNotNull('pingat')
        ->latest('id')
        ->take(10)
        ->get()
        ->map(function ($keputusan) {

            return [
                'acara' => $keputusan->acara->nama_acara ?? 'Merentas Desa',
                'peserta' => $keputusan->peserta->nama_peserta ?? '-',
                'rumah' => $keputusan->rumahSukan->nama_rumah ?? '-',
                'pingat' => $keputusan->pingat ?? '-',
                'mata' => $keputusan->mata,
            ];
        })
        ->values();
    }
}
