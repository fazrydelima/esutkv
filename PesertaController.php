<?php

namespace App\Http\Controllers;

use App\Models\Peserta;
use App\Models\Rumah_Sukan;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PesertaController extends Controller
{
    public function index()
    {
        $peserta = Peserta::with('rumahSukan')
            ->orderBy('nama_peserta')
            ->get();
        $rumahSukans = Rumah_Sukan::orderBy('nama_rumah')->get();

        return view('esutkv.peserta.index', compact('peserta', 'rumahSukans'));
    }

    public function senarai()
    {
        $peserta = Peserta::with('rumahSukan')
            ->orderBy('nama_peserta')
            ->get();

        return view('esutkv.peserta.senarai', compact('peserta'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_peserta' => 'required|string|max:255',
            'rumah_sukan_id' => 'required|exists:rumah_sukans,id',
            'jantina' => 'required|in:L,P',
        ]);

        Peserta::create($data);

        return redirect()
            ->route('peserta.index')
            ->with('success', 'Peserta berjaya didaftarkan.');
    }

    public function destroy(int $id)
    {
        DB::transaction(function () use ($id) {
            $peserta = Peserta::findOrFail($id);
            $peserta->keputusans()->delete();
            $peserta->delete();
        });

        return redirect()
            ->route('peserta.senarai')
            ->with('success', 'Peserta dan keputusan berkaitan berjaya dipadamkan.');
    }
}