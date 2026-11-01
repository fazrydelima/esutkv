<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Keputusan;
use App\Models\Peserta;
use App\Models\Rumah_Sukan;

class KejohananController extends Controller
{
    // Papar jadual keputusan
    public function show()
    {
        $kejohanan = Keputusan::all();

        return view('kejohanan.index', compact('kejohanan'));
    }

    // Papar borang input kejohanan
    public function create()
    {
        $acara = Acara::all();
        $peserta = Peserta::all();

        return view('kejohanan.create', compact('acara', 'peserta'));
    }

    // Simpan keputusan tempat pertama, kedua dan ketiga
    public function store(Request $request)
    {
        $data = $request->validate([
            'acara_id' => 'required|exists:acara,acara_id',

            'peserta_id.1' => 'required|exists:peserta,peserta_id|different:peserta_id.2|different:peserta_id.3',
            'peserta_id.2' => 'required|exists:peserta,peserta_id|different:peserta_id.3',
            'peserta_id.3' => 'required|exists:peserta,peserta_id',
        ]);

        // Skema mata contoh
        // Kemudian boleh digantikan dengan data jadual skema_mata
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

        return view('kejohanan.index_update', compact('kejohanan'));
    }

    // Cari ID dan papar borang update
    public function edit($keputusan_id)
    {
        $kejohanan = Keputusan::findOrFail($keputusan_id);

        $acara = Acara::all();
        $peserta = Peserta::all();

        return view(
            'kejohanan.update',
            compact('keputusan', 'acara', 'peserta')
        );
    }

    // Kemaskini satu rekod keputusan
    public function update(Request $request, $keputusan_id)
    {
        $data = $request->validate([
            'acara_id' => 'required|exists:acara,acara_id',
            'rumah_id' => 'required|exists:rumah_sukan,rumah_id',
            'peserta_id' => 'required|exists:peserta,peserta_id',
            'kedudukan' => 'required|integer|in:1,2,3',
            'mata' => 'required|integer|min:0',
        ]);

        $kejohanan = Keputusan::findOrFail($keputusan_id);
        $kejohanan->update($data);

        return redirect()
            ->route('kejohanan.index_update')
            ->with('success', 'Rekod berjaya dikemaskini.');
    }

    // Padam satu rekod keputusan
    public function delete($keputusan_id)
    {
        $kejohanan = Keputusan::findOrFail($keputusan_id);
        $kejohanan->delete();

        return redirect()
            ->route('kejohanan.index_update')
            ->with('success', 'Rekod berjaya dipadam.');
    }

    
    public function jumlahMata()
    {
        $jumlahMata = DB::table('rumah_sukan')
            ->leftJoin(
                'peserta',
                'rumah_sukan.rumah_id',
                '=',
                'peserta.rumah_id'
            ) 
            ->leftJoin(
                'keputusan',
                'peserta.peserta_id',
                '=',
                'keputusan.peserta_id'
            )
            ->select(
                'rumah_sukan.rumah_id',
                'rumah_sukan.nama_rumah',
                DB::raw('COALESCE(SUM(keputusan.mata), 0) as jumlah_mata')
            )
            ->groupBy(
                'rumah_sukan.rumah_id',
                'rumah_sukan.nama_rumah'
            )
            ->orderByDesc('jumlah_mata')
            ->get();

        return view('kejohanan.jumlah_mata', compact('jumlahMata'));
    }

}
