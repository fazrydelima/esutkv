<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Keputusan;
use App\Models\Peserta;
use App\Models\Rumah_Sukan;
use App\Models\Acara;

class KejohananController extends Controller
{
    // 1. Papar senarai acara dan keputusan kejohanan
    public function index()
    {
        $acara = Acara::with([
            'keputusan' => function ($query) {
                $query->where('jenis_keputusan', 'kejohanan')
                      ->with(['peserta', 'rumah_sukan']);
            }
        ])->get();

        return view('kejohanan.index', compact('acara'));
    }


    // 2. Papar borang tambah keputusan
    public function create()
    {
        $acara = Acara::all();
        $rumah = Rumah_Sukan::all();

        return view('kejohanan.create', compact(
            'acara',
            'rumah'
        ));
    }


    // 3. Simpan keputusan kejohanan
    public function store(Request $request)
    {
        $data = $request->validate([
            'acara_id' => 'required|exists:acara,acara_id',

            'peserta.1.nama' => 'required|string|max:255',
            'peserta.1.rumah_id' => 'required|exists:rumah_sukan,rumah_id',

            'peserta.2.nama' => 'required|string|max:255',
            'peserta.2.rumah_id' => 'required|exists:rumah_sukan,rumah_id',

            'peserta.3.nama' => 'required|string|max:255',
            'peserta.3.rumah_id' => 'required|exists:rumah_sukan,rumah_id',
        ]);

        $acaraId = $data['acara_id'];

        // Ambil skema mata berdasarkan acara
        $skemaMata = Skema_Mata::where('acara_id', $acaraId)
            ->pluck('mata', 'kedudukan');

        // Pastikan skema mata bagi ketiga-tiga kedudukan wujud
        foreach ([1, 2, 3] as $kedudukan) {
            if (!$skemaMata->has($kedudukan)) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'acara_id' => "Skema mata bagi kedudukan {$kedudukan} belum ditetapkan."
                    ]);
            }
        }

        DB::transaction(function () use (
            $data,
            $acaraId,
            $skemaMata
        ) {
            foreach ([1, 2, 3] as $kedudukan) {

                // Simpan maklumat peserta
                $peserta = Peserta::create([
                    'acara_id' => $acaraId,
                    'nama' => $data['peserta'][$kedudukan]['nama'],
                    'rumah_id' => $data['peserta'][$kedudukan]['rumah_id'],
                ]);

                // Ambil mata secara automatik
                $mata = $skemaMata[$kedudukan];

                // Simpan keputusan
                Keputusan::create([
                    'acara_id' => $acaraId,
                    'peserta_id' => $peserta->peserta_id,
                    'rumah_id' => $peserta->rumah_id,
                    'kedudukan' => $kedudukan,
                    'mata' => $mata,
                    'jenis_keputusan' => 'kejohanan',
                ]);
            }
        });

        return redirect()
            ->route('kejohanan.index')
            ->with('success', 'Keputusan kejohanan berjaya disimpan.');
    }


    // 4. Papar keputusan bagi sesuatu acara
    public function show($acaraId)
    {
        $acara = Acara::findOrFail($acaraId);

        $keputusan = Keputusan::with([
            'peserta',
            'rumah_sukan'
        ])
        ->where('acara_id', $acaraId)
        ->where('jenis_keputusan', 'kejohanan')
        ->orderBy('kedudukan')
        ->get();

        // Jumlah mata setiap rumah sukan bagi acara ini
        $jumlahMata = Keputusan::where('acara_id', $acaraId)
            ->where('jenis_keputusan', 'kejohanan')
            ->select(
                'rumah_id',
                DB::raw('SUM(mata) as jumlah_mata')
            )
            ->groupBy('rumah_id')
            ->with('rumah_sukan')
            ->get();

        return view('kejohanan.show', compact(
            'acara',
            'keputusan',
            'jumlahMata'
        ));
    }


    // 5. Papar borang kemaskini keputusan
    public function edit($acaraId)
    {
        $acara = Acara::findOrFail($acaraId);

        $rumah = Rumah_Sukan::all();

        $keputusan = Keputusan::with('peserta')
            ->where('acara_id', $acaraId)
            ->where('jenis_keputusan', 'kejohanan')
            ->get()
            ->keyBy('kedudukan');

        return view('kejohanan.edit', compact(
            'acara',
            'rumah',
            'keputusan'
        ));
    }


    // 6. Kemaskini keputusan kejohanan
    public function update(Request $request, $acaraId)
    {
        $data = $request->validate([
            'peserta.1.nama' => 'required|string|max:255',
            'peserta.1.rumah_id' => 'required|exists:rumah_sukan,rumah_id',

            'peserta.2.nama' => 'required|string|max:255',
            'peserta.2.rumah_id' => 'required|exists:rumah_sukan,rumah_id',

            'peserta.3.nama' => 'required|string|max:255',
            'peserta.3.rumah_id' => 'required|exists:rumah_sukan,rumah_id',
        ]);

        $acara = Acara::findOrFail($acaraId);

        // Ambil semula skema mata terkini
        $skemaMata = Skema_Mata::where('acara_id', $acaraId)
            ->pluck('mata', 'kedudukan');

        foreach ([1, 2, 3] as $kedudukan) {
            if (!$skemaMata->has($kedudukan)) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'skema' => "Skema mata bagi kedudukan {$kedudukan} belum ditetapkan."
                    ]);
            }
        }

        DB::transaction(function () use (
            $data,
            $acaraId,
            $skemaMata
        ) {
            // Ambil keputusan lama untuk acara ini sahaja
            $keputusanLama = Keputusan::where('acara_id', $acaraId)
                ->where('jenis_keputusan', 'kejohanan')
                ->get();

            $pesertaIds = $keputusanLama
                ->pluck('peserta_id')
                ->filter()
                ->unique();

            // Padam keputusan lama
            Keputusan::where('acara_id', $acaraId)
                ->where('jenis_keputusan', 'kejohanan')
                ->delete();

            // Padam peserta lama yang tidak lagi digunakan
            Peserta::whereIn('peserta_id', $pesertaIds)
                ->whereNotIn(
                    'peserta_id',
                    Keputusan::whereNotNull('peserta_id')
                        ->select('peserta_id')
                )
                ->delete();

            // Simpan semula tiga keputusan
            foreach ([1, 2, 3] as $kedudukan) {

                $peserta = Peserta::create([
                    'acara_id' => $acaraId,
                    'nama' => $data['peserta'][$kedudukan]['nama'],
                    'rumah_id' => $data['peserta'][$kedudukan]['rumah_id'],
                ]);

                Keputusan::create([
                    'acara_id' => $acaraId,
                    'peserta_id' => $peserta->peserta_id,
                    'rumah_id' => $peserta->rumah_id,
                    'kedudukan' => $kedudukan,
                    'mata' => $skemaMata[$kedudukan],
                    'jenis_keputusan' => 'kejohanan',
                ]);
            }
        });

        return redirect()
            ->route('kejohanan.index')
            ->with('success', 'Keputusan kejohanan berjaya dikemaskini.');
    }


    // 7. Padam keputusan kejohanan
    public function destroy($acaraId)
    {
        DB::transaction(function () use ($acaraId) {

            $keputusan = Keputusan::where('acara_id', $acaraId)
                ->where('jenis_keputusan', 'kejohanan')
                ->get();

            $pesertaIds = $keputusan
                ->pluck('peserta_id')
                ->filter()
                ->unique();

            // Padam keputusan kejohanan sahaja
            Keputusan::where('acara_id', $acaraId)
                ->where('jenis_keputusan', 'kejohanan')
                ->delete();

            // Padam peserta yang tidak lagi digunakan
            Peserta::whereIn('peserta_id', $pesertaIds)
                ->whereNotIn(
                    'peserta_id',
                    Keputusan::whereNotNull('peserta_id')
                        ->select('peserta_id')
                )
                ->delete();
        });

        return redirect()
            ->route('kejohanan.index')
            ->with('success', 'Keputusan kejohanan berjaya dipadam.');
    }

}
