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
    // Papar jadual keputusan
    public function show()
    {
        $kejohanan = Keputusan::all();

        return view('esutkv.kejohanan.index', compact('kejohanan'));
    }

    // Papar borang input kejohanan
    public function create()
    {
        $acara = Acara::all();
        $peserta = Peserta::all();

        return view('esutkv.kejohanan.index', compact('acara', 'peserta'));
    }

    // Simpan keputusan tempat pertama, kedua dan ketiga
    public function store(Request $request)
    {
        $data = $request->validate([
            'acara_id' => 'required|exists:acaras,id',

            'peserta_id.1' => 'required|exists:pesertas,id|different:peserta_id.2|different:peserta_id.3',
            'peserta_id.2' => 'required|exists:pesertas,id|different:peserta_id.3',
            'peserta_id.3' => 'required|exists:pesertas,id',
        ]);

        $skemaMata = [
            1 => 10,
            2 => 8,
            3 => 6,
        ];

        DB::transaction(function () use ($data, $skemaMata) {
            foreach ([1, 2, 3] as $kedudukan) {
                Keputusan::updateOrCreate(
                    [
                        'acara_id' => $data['acara_id'],
                        'kedudukan' => $kedudukan,
                    ],
                    [
                        'peserta_id' => $data['peserta_id'][$kedudukan],
                        'mata' => $skemaMata[$kedudukan],
                    ]
                );
            }
        });

        return redirect()
            ->route('kejohanan.index')
            ->with('success', 'Keputusan kejohanan berjaya disimpan.');
    }

    // Papar jadual untuk update/delete
    public function showupdate()
    {
        $kejohanan = Keputusan::all();

        return view('esutkv.kejohanan.index', compact('kejohanan'));
    }

    // Cari ID dan papar borang update
    public function edit($keputusan_id)
    {
        $kejohanan = Keputusan::findOrFail($keputusan_id);

        $acara = Acara::all();
        $peserta = Peserta::all();

        return view(
            'esutkv.kejohanan.update',
            compact('kejohanan', 'acara', 'peserta')
        );
    }

    // Kemaskini satu rekod keputusan
    public function update(Request $request, $keputusan_id)
    {
        $data = $request->validate([
            'acara_id' => 'required|exists:acaras,id',
            'peserta_id' => 'required|exists:pesertas,id',
            'kedudukan' => 'required|integer|in:1,2,3',
            'mata' => 'required|integer|min:0',
        ]);

        $kejohanan = Keputusan::findOrFail($keputusan_id);
        $kejohanan->update($data);

        return redirect()
            ->route('kejohanan.index')
            ->with('success', 'Rekod berjaya dikemaskini.');
    }

    // Padam satu rekod keputusan
    public function delete($keputusan_id)
    {
        $kejohanan = Keputusan::findOrFail($keputusan_id);
        $kejohanan->delete();

        return redirect()
            ->route('kejohanan.index')
            ->with('success', 'Rekod berjaya dipadam.');
    }

    // Kira jumlah mata rumah sukan
    public function jumlahMata()
    {
        $jumlahMata = DB::table('rumah_sukans')
            ->leftJoin(
                'pesertas',
                'rumah_sukans.id',
                '=',
                'pesertas.rumah_sukan_id'
            ) 
            ->leftJoin(
                'keputusans',
                'pesertas.id',
                '=',
                'keputusans.peserta_id'
            )
            ->select(
                'rumah_sukans.id',
                'rumah_sukans.nama_rumah',
                DB::raw('COALESCE(SUM(keputusans.mata), 0) as jumlah_mata')
            )
            ->groupBy(
                'rumah_sukans.id',
                'rumah_sukans.nama_rumah'
            )
            ->orderByDesc('jumlah_mata')
            ->get();

        return view('esutkv.kejohanan.index', compact('jumlahMata'));
    }
}
