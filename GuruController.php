<?php

namespace App\Http\Controllers;

use App\Models\Acara;
use App\Models\KeputusanAcara;
use App\Models\Peserta;
use Illuminate\Http\Request;

class GuruController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $acara = Acara::orderBy('nama_acara')->get();
        $peserta = Peserta::where('rumah_sukan_id', $user->rumah_sukan_id)
            ->orderBy('nama_peserta')
            ->get();
        $keputusans = KeputusanAcara::with('acara', 'peserta')
            ->where('submitted_by', $user->id)
            ->latest()
            ->get();

        return view('esutkv.guru.dashboard', compact('user', 'acara', 'peserta', 'keputusans'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'acara_id' => 'required|exists:acaras,id',
            'peserta_id' => 'required|exists:pesertas,id',
            'pingat' => 'required|in:Emas,Perak,Gangsa',
        ]);

        $user = $request->user();
        $peserta = Peserta::where('id', $data['peserta_id'])
            ->where('rumah_sukan_id', $user->rumah_sukan_id)
            ->first();

        if (! $peserta) {
            return back()
                ->withErrors(['peserta_id' => 'Guru hanya boleh menghantar keputusan peserta rumah sukan sendiri.'])
                ->withInput();
        }

        $sudahDihantar = KeputusanAcara::where('acara_id', $data['acara_id'])
            ->where('peserta_id', $peserta->id)
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        if ($sudahDihantar) {
            return back()
                ->withErrors(['peserta_id' => 'Keputusan peserta ini untuk acara tersebut sudah dihantar atau disahkan.'])
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
            'status' => 'pending',
            'submitted_by' => $user->id,
        ]);

        return redirect()
            ->route('guru.dashboard')
            ->with('success', 'Keputusan dihantar kepada admin untuk pengesahan.');
    }
}
