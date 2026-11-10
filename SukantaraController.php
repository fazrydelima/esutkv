<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\KeputusanAcara;
use App\Models\Rumah_Sukan;
use App\Models\Acara;

class SukantaraController extends Controller
{
   // READ: Paparkan senarai keputusan Sukantara
    public function index()
    {
        $keputusan = KeputusanAcara::with([
            'acara',
            'rumahSukan'
        ])
        ->whereNull('peserta_id')
        ->whereNull('pingat')
        ->whereNull('jenis_keputusan')
        ->orderByDesc('id')
        ->get();

        return view('esutkv.sukantara.index', compact('keputusan'));
    }


    // CREATE: Paparkan borang pengisian mata
    public function create()
    {
        $acara = Acara::orderBy('nama_acara')->get();

        $rumahSukan = Rumah_Sukan::orderBy('id')->get();

        return view('esutkv.sukantara.create', compact(
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
                'exists:acaras,id'
            ],

            'mata' => [
                'required',
                'array',
                'size:4'
            ],

            'mata.*' => [
                'required',
                'integer',
                'min:0'
            ],
        ]);

        $rumahSukan = Rumah_Sukan::orderBy('id')->get();

        // Pastikan terdapat tepat 4 rumah sukan
        if ($rumahSukan->count() != 4) {
            return back()->withInput()->withErrors([
                'mata' => 'Sistem perlu mempunyai tepat 4 rumah sukan.'
            ]);
        }

        // Pastikan input mengandungi ID keempat-empat rumah sukan
        $idRumah = $rumahSukan
            ->pluck('id')
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

        // Semak keputusan acara sedia ada
        $sudahWujud = KeputusanAcara::where('acara_id', $data['acara_id'])
            ->whereNull('peserta_id')
            ->whereNull('pingat')
            ->whereNull('jenis_keputusan')
            ->exists();

        if ($sudahWujud) {
            return back()->withInput()->withErrors([
                'acara_id' => 'Keputusan bagi acara ini sudah wujud. Sila gunakan fungsi kemas kini.'
            ]);
        }

        // Simpan empat rekod dalam satu transaksi
        DB::transaction(function () use ($data) {

            foreach ($data['mata'] as $rumahId => $mata) {

                KeputusanAcara::create([
                    'acara_id' => $data['acara_id'],
                    'peserta_id' => null,
                    'rumah_sukan_id' => $rumahId,
                    'pingat' => null,
                    'mata' => $mata,
                    'status',
                    'submitted_by' => null,
                    'reviewed_by' => null,
                    'reviewed_at' => null,
                ]);
            }
        });

        return redirect()
            ->route('sukantara.index')
            ->with('success', 'Mata Sukantara berjaya disimpan.');
    }


    // UPDATE: Paparkan borang kemas kini mata
    public function edit($acaraId)
    {
        $acara = Acara::findOrFail($acaraId);

        $rumahSukan = Rumah_Sukan::orderBy('id')->get();

        $keputusan = KeputusanAcara::where('acara_id', $acaraId)
            ->whereNull('peserta_id')
            ->whereNull('pingat')
            ->whereNull('jenis_keputusan')
            ->get()
            ->keyBy('rumah_sukan_id');

        if ($keputusan->isEmpty()) {
            return redirect()
                ->route('sukantara.index')
                ->with('error', 'Keputusan Sukantara tidak dijumpai.');
        }

        return view('esutkv.sukantara.update', compact(
            'acara',
            'rumahSukan',
            'keputusan'
        ));
    }


    // UPDATE: Simpan perubahan mata
    public function update(Request $request, $acaraId)
    {
        $acara = Acara::findOrFail($acaraId);

        $data = $request->validate([
            'mata' => [
                'required',
                'array',
                'size:4'
            ],

            'mata.*' => [
                'required',
                'integer',
                'min:0'
            ],
        ]);

        $rumahSukan = Rumah_Sukan::orderBy('id')->get();

        // Pastikan terdapat tepat 4 rumah sukan
        if ($rumahSukan->count() != 4) {
            return back()->withInput()->withErrors([
                'mata' => 'Sistem perlu mempunyai tepat 4 rumah sukan.'
            ]);
        }

        // Semak ID rumah sukan
        $idRumah = $rumahSukan
            ->pluck('id')
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

        // Pastikan rekod Sukantara wujud
        $sudahWujud = KeputusanAcara::where('acara_id', $acaraId)
            ->whereNull('peserta_id')
            ->whereNull('pingat')
            ->whereNull('jenis_keputusan')
            ->exists();

        if (!$sudahWujud) {
            return redirect()
                ->route('sukantara.index')
                ->with('error', 'Keputusan Sukantara tidak dijumpai.');
        }

        // Kemas kini rekod mengikut rumah sukan
        DB::transaction(function () use ($data, $acaraId) {

            foreach ($data['mata'] as $rumahId => $mata) {

                KeputusanAcara::updateOrCreate(

                    [
                        'acara_id' => $acaraId,
                        'rumah_sukan_id' => $rumahId,
                        'peserta_id' => null,
                        'pingat' => null,
                    ],

                    [
                        'mata' => $mata,
                        'status',
                        'submitted_by' => null,
                        'reviewed_by' => null,
                        'reviewed_at' => null,
                    ]
                );
            }
        });

        return redirect()
            ->route('sukantara.index')
            ->with('success', 'Mata Sukantara berjaya dikemaskini.');
    }


    // DELETE: Padam semua keputusan Sukantara bagi satu acara
    public function destroy($acaraId)
    {
        Acara::findOrFail($acaraId);

        $jumlahPadam = KeputusanAcara::where('acara_id', $acaraId)
            ->whereNull('peserta_id')
            ->whereNull('pingat')
            ->whereNull('jenis_keputusan')
            ->delete();

        if ($jumlahPadam == 0) {
            return redirect()
                ->route('sukantara.index')
                ->with('error', 'Keputusan Sukantara tidak dijumpai.');
        }

        return redirect()
            ->route('sukantara.index')
            ->with('success', 'Keputusan Sukantara berjaya dipadam.');
    }
}
