<?php

namespace App\Http\Controllers;

use App\Models\Acara;
use App\Models\KeputusanAcara;
use App\Models\Peserta;
use App\Models\Rumah_Sukan;
use Illuminate\Http\Request;

class GuruController extends Controller
{
    public function index()
    {
        $user = auth()->user();

    $jumlahPeserta = Peserta::where(
        'rumah_sukan_id',
        $user->rumah_sukan_id
    )->count();

    $jumlahMata = KeputusanAcara::where(
        'rumah_sukan_id',
        $user->rumah_sukan_id
    )->sum('mata');

    $keputusans = KeputusanAcara::with([
        'acara',
        'peserta',
        'rumahSukan'
    ])
        ->where('rumah_sukan_id', $user->rumah_sukan_id)
        ->orderByDesc('id')
        ->take(10)
        ->get();

    return view('esutkv.guru.dashboard', compact(
        'user',
        'jumlahPeserta',
        'jumlahMata',
        'keputusans'
    ));
    }

    // Menghantar keputusan oleh guru (jika fungsi ini masih digunakan)
    public function store(Request $request)
    {
        $data = $request->validate([
            'acara_id' => 'required|exists:acaras,id',
            'peserta_id' => 'required|exists:pesertas,id',
            'pingat' => 'required|in:Emas,Perak,Gangsa',
        ]);

        $user = $request->user();

        // Pastikan peserta daripada rumah sukan guru sendiri
        $peserta = Peserta::where('id', $data['peserta_id'])
            ->where('rumah_sukan_id', $user->rumah_sukan_id)
            ->first();

        if (!$peserta) {
            return back()
                ->withErrors([
                    'peserta_id' => 'Guru hanya boleh memilih peserta rumah sukan sendiri.'
                ])
                ->withInput();
        }

        // Semak keputusan sedia ada tanpa menggunakan status
        $sudahDihantar = KeputusanAcara::where(
            'acara_id',
            $data['acara_id']
        )
        ->where('peserta_id', $peserta->id)
        ->exists();

        if ($sudahDihantar) {
            return back()
                ->withErrors([
                    'peserta_id' => 'Keputusan peserta ini untuk acara tersebut sudah wujud.'
                ])
                ->withInput();
        }

        $mataPingat = [
            'Emas' => 10,
            'Perak' => 8,
            'Gangsa' => 6,
        ];

        KeputusanAcara::create([
            'acara_id' => $data['acara_id'],
            'peserta_id' => $peserta->id,
            'rumah_sukan_id' => $user->rumah_sukan_id,
            'pingat' => $data['pingat'],
            'mata' => $mataPingat[$data['pingat']],
            'submitted_by' => $user->id,
        ]);

        return redirect()
            ->route('guru.dashboard')
            ->with('success', 'Keputusan berjaya dihantar.');
    }
}
