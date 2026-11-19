<?php

namespace App\Http\Controllers;

use App\Models\Peserta;
use App\Models\Rumah_Sukan;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PesertaController extends Controller
{
    private function rumahSukanGuru()
    {
        $rumahSukanId = auth()->user()->rumah_sukan_id;

        if (!$rumahSukanId) {
            abort(403, 'Akaun guru belum ditetapkan kepada rumah sukan.');
        }

        return $rumahSukanId;
    }

    // Paparan senarai peserta
    public function index()
    {
        $rumahSukanId = $this->rumahSukanGuru();

        $peserta = Peserta::with('rumahSukan')
            ->where('rumah_sukan_id', $rumahSukanId)
            ->orderBy('nama_peserta')
            ->get();

        return view('esutkv.guru.peserta.index', compact('peserta'));
    }

    // Paparan senarai peserta
    public function senarai()
    {
        return $this->index();
    }

    // Simpan peserta baharu
    public function store(Request $request)
    {
        $rumahSukanId = $this->rumahSukanGuru();

        $data = $request->validate([
            'nama_peserta' => 'required|string|max:255',
            'jantina' => 'required|in:L,P',
        ]);

        // Rumah sukan ditentukan melalui akaun guru
        $data['rumah_sukan_id'] = $rumahSukanId;

        Peserta::create($data);

        return redirect()
            ->route('peserta.index')
            ->with('success', 'Peserta berjaya didaftarkan.');
    }

    // Paparan borang kemaskini
    public function edit(int $id)
    {
        $rumahSukanId = $this->rumahSukanGuru();

        $peserta = Peserta::where('rumah_sukan_id', $rumahSukanId)
            ->findOrFail($id);

        return view('esutkv.guru.peserta.edit', compact('peserta'));
    }

    // Kemaskini maklumat peserta
    public function update(Request $request, int $id)
    {
        $rumahSukanId = $this->rumahSukanGuru();

        $peserta = Peserta::where('rumah_sukan_id', $rumahSukanId)
            ->findOrFail($id);

        $data = $request->validate([
            'nama_peserta' => 'required|string|max:255',
            'jantina' => 'required|in:L,P',
        ]);

        $peserta->update($data);

        return redirect()
            ->route('peserta.index')
            ->with('success', 'Maklumat peserta berjaya dikemaskini.');
    }

    // Padam peserta
    public function destroy(int $id)
    {
        $rumahSukanId = $this->rumahSukanGuru();

        $peserta = Peserta::where('rumah_sukan_id', $rumahSukanId)
            ->findOrFail($id);

        // Elakkan keputusan pertandingan terpadam
        if ($peserta->keputusans()->exists()) {
            return redirect()
                ->route('peserta.index')
                ->with('error', 'Peserta tidak boleh dipadam kerana mempunyai rekod keputusan pertandingan.');
        }

        $peserta->delete();

        return redirect()
            ->route('peserta.index')
            ->with('success', 'Peserta berjaya dipadamkan.');
    }
}
