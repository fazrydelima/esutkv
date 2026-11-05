<?php

namespace App\Http\Controllers;

use App\Models\Acara;
use App\Models\KeputusanAcara;
use App\Models\Peserta;
use Illuminate\Http\Request;

class KeputusanController extends Controller
{
    public function index()
    {
        $acara = Acara::orderBy('nama_acara')->get();
        $peserta = Peserta::with('rumahSukan')
            ->orderBy('nama_peserta')
            ->get();
        $keputusans = KeputusanAcara::with(['acara', 'peserta.rumahSukan'])
            ->latest()
            ->get();

        return view('esutkv.keputusan.index', compact('acara', 'peserta', 'keputusans'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'acara_id' => 'required|exists:acaras,id',
            'peserta_id' => 'required|exists:pesertas,id',
            'pingat' => 'required|in:Emas,Perak,Gangsa',
        ]);

        $sudahDirekod = KeputusanAcara::where('acara_id', $data['acara_id'])
            ->where('peserta_id', $data['peserta_id'])
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        if ($sudahDirekod) {
            return back()
                ->withErrors(['peserta_id' => 'Keputusan peserta ini untuk acara tersebut sudah dihantar atau disahkan.'])
                ->withInput();
        }

        $mataPingat = [
            'Emas' => 10,
            'Perak' => 8,
            'Gangsa' => 6,
        ];
        $pingatSudahDirekod = KeputusanAcara::where('acara_id', $data['acara_id'])
            ->where('pingat', $data['pingat'])
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        if ($pingatSudahDirekod) {
            return back()
                ->withErrors(['pingat' => 'Pingat tersebut sudah dihantar atau direkodkan untuk acara ini.'])
                ->withInput();
        }

        $peserta = Peserta::findOrFail($data['peserta_id']);

        KeputusanAcara::create([
            'acara_id' => $data['acara_id'],
            'peserta_id' => $peserta->id,
            'rumah_sukan_id' => $peserta->rumah_sukan_id,
            'pingat' => $data['pingat'],
            'mata' => $mataPingat[$data['pingat']],
            'status' => 'approved',
            'submitted_by' => $request->user()->id,
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => now(),
        ]);

        return redirect()
            ->route('keputusan.index')
            ->with('success', 'Keputusan berjaya direkodkan.');
    }
}