<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Keputusan;
use App\Models\Peserta;
use App\Models\Rumah_Sukan;
use App\Models\Acara;

class SukantaraController extends Controller
{
    // READ: Paparkan senarai keputusan Sukantara
    public function index()
    {
        $keputusan = Keputusan::with([
            'acara',
            'rumah_sukan'
        ])
        ->where('jenis_keputusan', 'sukantara')
        ->orderBy('acara_id')
        ->get();

        return view('sukantara.index', compact('keputusan'));
    }

    // CREATE: Paparkan borang pengisian mata
    public function create()
    {
        $acara = Acara::all();
        $rumahSukan = Rumah_Sukan::all();

        return view('sukantara.create', compact(
            'acara',
            'rumahSukan'
        ));
    }

    // CREATE: Simpan mata bagi 4 rumah sukan
    public function store(Request $request)
    {
        $data = $request->validate([
            'acara_id' => [
                'required',
                'integer',
                'exists:acara,acara_id'
            ],

            'mata' => [
                'required',
                'array',
            ],

            'mata.*' => [
                'required',
                'integer',
                'min:0',
            ],
        ]);

        $rumahSukan = Rumah_Sukan::all();

        // Pastikan terdapat 4 rumah sukan
        if ($rumahSukan->count() != 4) {
            return back()->withInput()->withErrors([
                'mata' => 'Sistem perlu mempunyai tepat 4 rumah sukan.'
            ]);
        }

        // Pastikan admin mengisi mata untuk semua rumah sukan
        $idRumah = $rumahSukan
            ->pluck('rumah_id')
            ->map(fn ($id) => (string) $id)
            ->sort()
            ->values()
            ->all();

        $idInput = collect(array_keys($data['mata']))
            ->map(fn ($id) => (string) $id)
            ->sort()
            ->values()
            ->all();

        if ($idRumah !== $idInput) {
            return back()->withInput()->withErrors([
                'mata' => 'Sila isi mata untuk keempat-empat rumah sukan.'
            ]);
        }

        DB::transaction(function () use ($data) {

            // Padam rekod Sukantara terdahulu bagi acara ini sahaja
            Keputusan::where('acara_id', $data['acara_id'])
                ->where('jenis_keputusan', 'sukantara')
                ->delete();

            // Simpan mata setiap rumah sukan
            foreach ($data['mata'] as $rumahId => $mata) {

                Keputusan::create([
                    'acara_id' => $data['acara_id'],
                    'rumah_id' => $rumahId,
                    'peserta_id' => null,
                    'kedudukan' => null,
                    'mata' => $mata,
                    'jenis_keputusan' => 'sukantara',
                ]);
            }
        });

        return redirect()
            ->route('sukantara.index')
            ->with('success', 'Mata Sukantara berjaya disimpan.');
    }

    // READ: Paparkan keputusan bagi satu acara
    public function show($acaraId)
    {
        $acara = Acara::findOrFail($acaraId);

        $keputusan = Keputusan::with('rumah_sukan')
            ->where('acara_id', $acaraId)
            ->where('jenis_keputusan', 'sukantara')
            ->get();

        return view('sukantara.show', compact(
            'acara',
            'keputusan'
        ));
    }

    // UPDATE: Paparkan borang kemas kini mata
    public function edit($acaraId)
    {
        $acara = Acara::findOrFail($acaraId);

        $rumahSukan = Rumah_Sukan::all();

        $keputusan = Keputusan::where('acara_id', $acaraId)
            ->where('jenis_keputusan', 'sukantara')
            ->get()
            ->keyBy('rumah_id');

        return view('sukantara.edit', compact(
            'acara',
            'rumahSukan',
            'keputusan'
        ));
    }

    // UPDATE: Simpan perubahan mata
    public function update(Request $request, $acaraId)
    {
        $request->merge([
            'acara_id' => $acaraId
        ]);

        // Gunakan validasi yang sama seperti store()
        $request->validate([
            'acara_id' => 'required|exists:acara,acara_id',
            'mata' => 'required|array',
            'mata.*' => 'required|integer|min:0',
        ]);

        $data = $request->all();

        $rumahSukan = Rumah_Sukan::all();

        if ($rumahSukan->count() != 4) {
            return back()->withInput()->withErrors([
                'mata' => 'Sistem perlu mempunyai tepat 4 rumah sukan.'
            ]);
        }

        $idRumah = $rumahSukan
            ->pluck('rumah_id')
            ->map(fn ($id) => (string) $id)
            ->sort()
            ->values()
            ->all();

        $idInput = collect(array_keys($data['mata']))
            ->map(fn ($id) => (string) $id)
            ->sort()
            ->values()
            ->all();

        if ($idRumah !== $idInput) {
            return back()->withInput()->withErrors([
                'mata' => 'Sila isi mata untuk keempat-empat rumah sukan.'
            ]);
        }

        DB::transaction(function () use ($data, $acaraId) {

            // Gantikan semua mata lama dengan mata baharu
            Keputusan::where('acara_id', $acaraId)
                ->where('jenis_keputusan', 'sukantara')
                ->delete();

            foreach ($data['mata'] as $rumahId => $mata) {

                Keputusan::create([
                    'acara_id' => $acaraId,
                    'rumah_id' => $rumahId,
                    'peserta_id' => null,
                    'kedudukan' => null,
                    'mata' => $mata,
                    'jenis_keputusan' => 'sukantara',
                ]);
            }
        });

        return redirect()
            ->route('sukantara.index')
            ->with('success', 'Mata Sukantara berjaya dikemaskini.');
    }

    // DELETE: Padam semua keputusan Sukantara bagi satu acara
    public function destroy($acaraId)
    {
        Keputusan::where('acara_id', $acaraId)
            ->where('jenis_keputusan', 'sukantara')
            ->delete();

        return redirect()
            ->route('sukantara.index')
            ->with('success', 'Keputusan Sukantara berjaya dipadam.');
    }
}
