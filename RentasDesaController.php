<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Keputusan;
use App\Models\Peserta;
use App\Models\Rumah_Sukan;
use App\Models\Skema_Mata;
use App\Models\Acara;

class RentasDesaController extends Controller
{
   // READ: Senarai keputusan Rentas Desa
    public function index()
    {
        $acara = Acara::all();

        $keputusan = Keputusan::with([
            'acara',
            'peserta',
            'rumah_sukan'
        ])
        ->whereIn('jenis_keputusan', [
            'terawal',
            'masa_terhad'
        ])
        ->orderBy('acara_id')
        ->orderByRaw('kedudukan IS NULL')
        ->orderBy('kedudukan')
        ->get();

        return view('rentas_desa.index', compact(
            'acara',
            'keputusan'
        ));
    }

    // CREATE: Paparkan borang
    public function create()
    {
        $acara = Acara::all();
        $rumahSukan = Rumah_Sukan::all();

        return view('rentas_desa.create', compact(
            'acara',
            'rumahSukan'
        ));
    }

    // CREATE: Simpan 20 atlet dan mata counter
    public function store(Request $request)
    {
        $data = $request->validate([
            'acara_id' => [
                'required',
                'integer',
                'exists:acara,acara_id',
            ],

            'atlet' => 'required|array|size:20',

            'atlet.*.nama' => [
                'required',
                'string',
                'max:255',
            ],

            'atlet.*.rumah_id' => [
                'required',
                'integer',
                'exists:rumah_sukan,rumah_id',
            ],

            'counter' => 'required|array',

            'counter.*' => [
                'required',
                'integer',
                'min:0',
            ],
        ]);

        if (array_sum($data['counter']) > 20) {
            return back()
                ->withInput()
                ->withErrors([
                    'counter' =>
                        'Jumlah atlet dalam masa terhad tidak boleh melebihi 20.'
                ]);
        }

        foreach (array_keys($data['counter']) as $rumahId) {
            if (!Rumah_Sukan::where(
                'rumah_id',
                $rumahId
            )->exists()) {
                return back()->withInput()->withErrors([
                    'counter' => 'Rumah sukan tidak sah.'
                ]);
            }
        }

        // Mata kedudukan 1 hingga 20: 20 hingga 1
        $mataPerKedudukan = [];

        for ($i = 1; $i <= 20; $i++) {
            $mataPerKedudukan[$i] = 21 - $i;
        }

        // Contoh: satu mata bagi setiap atlet counter
        $mataPerCounter = 1;

        DB::transaction(function () use (
            $data,
            $mataPerKedudukan,
            $mataPerCounter
        ) {
            // Buang keputusan Rentas Desa lama untuk acara ini sahaja
            $keputusanLama = Keputusan::where(
                'acara_id',
                $data['acara_id']
            )
            ->whereIn('jenis_keputusan', [
                'terawal',
                'masa_terhad'
            ])
            ->get();

            $pesertaLama = $keputusanLama
                ->pluck('peserta_id')
                ->filter()
                ->unique();

            Keputusan::where('acara_id', $data['acara_id'])
                ->whereIn('jenis_keputusan', [
                    'terawal',
                    'masa_terhad'
                ])
                ->delete();

            // Cipta semula peserta hanya jika tiada lagi keputusan
            // yang merujuk kepada peserta lama tersebut.
            foreach ($pesertaLama as $pesertaId) {
                $masihDigunakan = Keputusan::where(
                    'peserta_id',
                    $pesertaId
                )->exists();

                if (!$masihDigunakan) {
                    Peserta::where('peserta_id', $pesertaId)
                        ->where('acara_id', $data['acara_id'])
                        ->delete();
                }
            }

            // Simpan 20 atlet terawal
            foreach ($data['atlet'] as $index => $atlet) {
                $peserta = Peserta::create([
                    'nama' => $atlet['nama'],
                    'rumah_id' => $atlet['rumah_id'],
                    'acara_id' => $data['acara_id'],
                ]);

                $kedudukan = $index + 1;

                Keputusan::create([
                    'acara_id' => $data['acara_id'],
                    'peserta_id' => $peserta->peserta_id,
                    'rumah_id' => $atlet['rumah_id'],
                    'kedudukan' => $kedudukan,
                    'mata' => $mataPerKedudukan[$kedudukan],
                    'jenis_keputusan' => 'terawal',
                ]);
            }

            // Simpan mata counter mengikut rumah sukan
            foreach ($data['counter'] as $rumahId => $bilangan) {
                Keputusan::create([
                    'acara_id' => $data['acara_id'],
                    'peserta_id' => null,
                    'rumah_id' => $rumahId,
                    'kedudukan' => null,
                    'mata' => (int) $bilangan * $mataPerCounter,
                    'jenis_keputusan' => 'masa_terhad',
                ]);
            }
        });

        return redirect()
            ->route('rentas_desa.index')
            ->with('success', 'Keputusan Rentas Desa berjaya disimpan.');
    }

    // READ: Paparkan keputusan untuk satu acara
    public function show($acaraId)
    {
        $acara = Acara::findOrFail($acaraId);

        $keputusan = Keputusan::with([
            'peserta',
            'rumah_sukan'
        ])
        ->where('acara_id', $acaraId)
        ->whereIn('jenis_keputusan', [
            'terawal',
            'masa_terhad'
        ])
        ->orderByRaw('kedudukan IS NULL')
        ->orderBy('kedudukan')
        ->get();

        $rumahSukan = Rumah_Sukan::all();

        $keputusanRumah = $rumahSukan->map(function ($rumah) use (
            $acaraId
        ) {
            $mataTerawal = Keputusan::where('acara_id', $acaraId)
                ->where('rumah_id', $rumah->rumah_id)
                ->where('jenis_keputusan', 'terawal')
                ->sum('mata');

            $mataMasaTerhad = Keputusan::where('acara_id', $acaraId)
                ->where('rumah_id', $rumah->rumah_id)
                ->where('jenis_keputusan', 'masa_terhad')
                ->sum('mata');

            return [
                'rumah_id' => $rumah->rumah_id,
                'nama_rumah' => $rumah->nama_rumah,
                'mata_20_terawal' => $mataTerawal,
                'mata_masa_terhad' => $mataMasaTerhad,
                'jumlah_mata' => $mataTerawal + $mataMasaTerhad,
            ];
        })->sortByDesc('jumlah_mata')->values();

        return view('rentas_desa.show', compact(
            'acara',
            'keputusan',
            'keputusanRumah'
        ));
    }

    // UPDATE: Paparkan borang kemas kini atlet
    public function edit($id)
    {
        $keputusan = Keputusan::where(
            'jenis_keputusan',
            'terawal'
        )->findOrFail($id);

        $keputusan->load('peserta', 'rumah_sukan');

        $rumahSukan = Rumah_Sukan::all();

        return view('rentas_desa.edit', compact(
            'keputusan',
            'rumahSukan'
        ));
    }

    // UPDATE: Simpan perubahan atlet
    public function update(Request $request, $id)
    {
        $keputusan = Keputusan::where(
            'jenis_keputusan',
            'terawal'
        )->findOrFail($id);

        $data = $request->validate([
            'nama' => 'required|string|max:255',

            'rumah_id' => [
                'required',
                'integer',
                'exists:rumah_sukan,rumah_id',
            ],

            'kedudukan' => 'required|integer|between:1,20',
        ]);

        DB::transaction(function () use ($data, $keputusan) {
            $peserta = Peserta::findOrFail(
                $keputusan->peserta_id
            );

            $peserta->update([
                'nama' => $data['nama'],
                'rumah_id' => $data['rumah_id'],
            ]);

            $keputusan->update([
                'rumah_id' => $data['rumah_id'],
                'kedudukan' => $data['kedudukan'],
                'mata' => 21 - $data['kedudukan'],
            ]);
        });

        return redirect()
            ->route('rentas_desa.index')
            ->with('success', 'Keputusan Rentas Desa berjaya dikemaskini.');
    }

    // DELETE: Padam satu rekod keputusan
    public function destroy($id)
    {
        $keputusan = Keputusan::whereIn('jenis_keputusan', [
            'terawal',
            'masa_terhad'
        ])->findOrFail($id);

        DB::transaction(function () use ($keputusan) {
            $pesertaId = $keputusan->peserta_id;
            $acaraId = $keputusan->acara_id;

            $keputusan->delete();

            // Padam peserta hanya jika tiada keputusan lain
            // yang masih menggunakan peserta tersebut.
            if ($pesertaId) {
                $masihDigunakan = Keputusan::where(
                    'peserta_id',
                    $pesertaId
                )->exists();

                if (!$masihDigunakan) {
                    Peserta::where('peserta_id', $pesertaId)
                        ->where('acara_id', $acaraId)
                        ->delete();
                }
            }
        });

        return redirect()
            ->route('rentas_desa.index')
            ->with('success', 'Rekod Rentas Desa berjaya dipadam.');
    }
}
