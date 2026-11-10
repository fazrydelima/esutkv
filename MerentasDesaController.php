<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\KeputusanAcara;
use App\Models\Peserta;
use App\Models\Rumah_Sukan;
use App\Models\Acara;

class MerentasDesaController extends Controller
{
   // READ: Paparkan senarai keputusan Rentas Desa
    public function index()
    {
        $keputusan = KeputusanAcara::with([
        'peserta',
        'rumahSukan'
    ])
    ->whereIn('jenis_keputusan', [
        'terawal',
        'masa_terhad'
    ])
    ->orderByRaw('kedudukan IS NULL')
    ->orderBy('kedudukan')
    ->get();

    $rumahSukan = Rumah_Sukan::orderBy('id')->get();

    return view('esutkv.merentas_desa.index', compact(
        'keputusan',
        'rumahSukan'
    ));
    }


    // CREATE: Paparkan borang Rentas Desa
    public function create()
    {
       $rumahSukan = Rumah_Sukan::orderBy('id')->get();

        return view('esutkv.merentas_desa.create', compact('rumahSukan'));
    }


    // CREATE: Simpan 20 atlet terawal dan mata counter
    public function store(Request $request)
    {
        $data = $request->validate([

            // 20 atlet terawal
            'atlet' => [
                'required',
                'array',
                'size:20'
            ],

            'atlet.*.nama' => [
                'required',
                'string',
                'max:255'
            ],

            'atlet.*.rumah_sukan_id' => [
                'required',
                'integer',
                'exists:rumah_sukans,id'
            ],

            // Counter masa terhad
            'counter' => [
                'required',
                'array',
                'size:4'
            ],

            'counter.*' => [
                'required',
                'integer',
                'min:0'
            ],

        ]);

        // Pastikan terdapat tepat 4 rumah sukan
        $rumahSukan = Rumah_Sukan::orderBy('id')->get();

        if ($rumahSukan->count() != 4) {
            return back()->withInput()->withErrors([
                'counter' => 'Sistem perlu mempunyai tepat 4 rumah sukan.'
            ]);
        }

        // Pastikan input counter mempunyai ID semua rumah sukan
        $idRumah = $rumahSukan
            ->pluck('id')
            ->map(fn ($id) => (string) $id)
            ->sort()
            ->values()
            ->all();

        $idInput = collect(array_keys($data['counter']))
            ->map(fn ($id) => (string) $id)
            ->sort()
            ->values()
            ->all();

        if ($idRumah !== $idInput) {
            return back()->withInput()->withErrors([
                'counter' => 'Sila isi counter untuk keempat-empat rumah sukan.'
            ]);
        }

        // Jumlah atlet masa terhad tidak boleh melebihi 20
        if (array_sum($data['counter']) > 20) {
            return back()->withInput()->withErrors([
                'counter' => 'Jumlah atlet masa terhad tidak boleh melebihi 20.'
            ]);
        }

        // Semak sama ada keputusan Rentas Desa sudah wujud
       $sudahWujud = KeputusanAcara::whereIn('jenis_keputusan', [
            'terawal',
            'masa_terhad'
        ])->exists();

        if ($sudahWujud) {
            return back()->withInput()->withErrors([
                'keputusan' => 'Keputusan Merentas Desa sudah wujud. Sila gunakan fungsi kemas kini.'
            ]);
        }

        // Mata kedudukan:
        // 1 = 20 mata
        // 2 = 19 mata
        // ...
        // 20 = 1 mata
        $mataPerKedudukan = [];

        for ($i = 1; $i <= 20; $i++) {
            $mataPerKedudukan[$i] = 21 - $i;
        }

        // Setiap atlet masa terhad mendapat 1 mata
        $mataPerCounter = 1;


        DB::transaction(function () use (
            $data,
            $mataPerKedudukan,
            $mataPerCounter
        ) {

            // SIMPAN 20 ATLET TERAWAL
            foreach ($data['atlet'] as $index => $atlet) {

                $kedudukan = $index + 1;

                // Simpan maklumat peserta
                $peserta = Peserta::create([
                    'nama_peserta' => $atlet['nama'],
                    'rumah_sukan_id' => $atlet['rumah_sukan_id'],
                    'jantina' => null,
                ]);

                // Simpan keputusan atlet
                KeputusanAcara::create([
                    'acara_id' => null,
                    'peserta_id' => $peserta->id,
                    'rumah_sukan_id' => $atlet['rumah_sukan_id'],
                    'pingat' => null,
                    'kedudukan' => $kedudukan,
                    'mata' => $mataPerKedudukan[$kedudukan],
                    'jenis_keputusan' => 'terawal',
                    'status',
                    'submitted_by' => null,
                    'reviewed_by' => null,
                    'reviewed_at' => null,
                ]);
            }


            // SIMPAN MATA COUNTER MENGIKUT RUMAH SUKAN
            foreach ($data['counter'] as $rumahId => $bilangan) {

                KeputusanAcara::create([
                    'acara_id' => null,
                    'peserta_id' => null,
                    'rumah_sukan_id' => $rumahId,
                    'pingat' => null,
                    'kedudukan' => null,
                    'mata' => (int) $bilangan * $mataPerCounter,
                    'jenis_keputusan' => 'masa_terhad',
                    'status',
                    'submitted_by' => null,
                    'reviewed_by' => null,
                    'reviewed_at' => null,
                ]);
            }

        });


        return redirect()
            ->route('merentas_desa.index')
            ->with('success', 'Keputusan Rentas Desa berjaya disimpan.');
    }


    // READ: Paparkan keputusan bagi satu acara
    public function show()
    {
       $keputusan = KeputusanAcara::with([
        'peserta',
        'rumahSukan'
    ])
    ->whereIn('jenis_keputusan', [
        'terawal',
        'masa_terhad'
    ])
    ->get();

    $rumahSukan = Rumah_Sukan::orderBy('id')->get();

    $keputusanRumah = $rumahSukan->map(function ($rumah) use ($keputusan) {

        $rekod = $keputusan->where(
            'rumah_sukan_id',
            $rumah->id
        );

        $mataTerawal = $rekod
            ->where('jenis_keputusan', 'terawal')
            ->sum('mata');

        $mataMasaTerhad = $rekod
            ->where('jenis_keputusan', 'masa_terhad')
            ->sum('mata');

        return [
            'rumah_sukan_id' => $rumah->id,
            'nama_rumah' => $rumah->nama_rumah,
            'mata_20_terawal' => $mataTerawal,
            'mata_masa_terhad' => $mataMasaTerhad,
            'jumlah_mata' => $mataTerawal + $mataMasaTerhad,
        ];

    })->sortByDesc('jumlah_mata')->values();

    return view('esutkv.merentas_desa.show', compact(
        'keputusan',
        'keputusanRumah'
        ));
    }

    public function edit($id)
    {
    // Cari rekod 20 atlet terawal
    $keputusan = KeputusanAcara::where(
        'jenis_keputusan',
        'terawal'
    )->findOrFail($id);

    // Dapatkan maklumat peserta dan rumah sukan
    $keputusan->load([
        'peserta',
        'rumahSukan'
    ]);

    // Senarai semua rumah sukan
    $rumahSukan = Rumah_Sukan::orderBy('id')->get();

    return view('esutkv.merentas_desa.update', compact(
        'keputusan',
        'rumahSukan'
    ));
    }

    // UPDATE: Simpan perubahan maklumat atlet
    public function update(Request $request, $id)
    {
        $keputusan = KeputusanAcara::where(
            'jenis_keputusan',
            'terawal'
        )->findOrFail($id);

        $data = $request->validate([

            'nama_peserta' => [
                'required',
                'string',
                'max:255'
            ],

            'rumah_sukan_id' => [
                'required',
                'integer',
                'exists:rumah_sukans,id'
            ],

            'kedudukan' => [
                'required',
                'integer',
                'between:1,20'
            ],

        ]);

       $kedudukanWujud = KeputusanAcara::where(
            'jenis_keputusan',
            'terawal'
        )
        ->where('kedudukan', $data['kedudukan'])
        ->where('id', '!=', $keputusan->id)
        ->exists();

        if ($kedudukanWujud) {
            return back()->withInput()->withErrors([
                'kedudukan' => 'Kedudukan ini sudah digunakan oleh atlet lain.'
            ]);
        }

        DB::transaction(function () use ($data, $keputusan) {

            // Kemas kini maklumat peserta
            $peserta = Peserta::findOrFail(
                $keputusan->peserta_id
            );

            $peserta->update([
                'nama_peserta' => $data['nama_peserta'],
                'rumah_sukan_id' => $data['rumah_sukan_id'],
            ]);

            // Kemas kini keputusan atlet
            $keputusan->update([
                'rumah_sukan_id' => $data['rumah_sukan_id'],
                'kedudukan' => $data['kedudukan'],
                'mata' => 21 - $data['kedudukan'],
            ]);

        });


        return redirect()
            ->route('merentas_desa.index')
            ->with('success', 'Keputusan Rentas Desa berjaya dikemaskini.');
    }


    // DELETE: Padam satu rekod keputusan Rentas Desa
    public function destroy($id)
    {
        $keputusan = KeputusanAcara::whereIn(
            'jenis_keputusan',
            ['terawal', 'masa_terhad']
        )->findOrFail($id);

        DB::transaction(function () use ($keputusan) {

            $pesertaId = $keputusan->peserta_id;

            // Padam rekod keputusan
            $keputusan->delete();

            // Padam peserta jika tiada keputusan lain menggunakannya
            if ($pesertaId) {

                $masihDigunakan = KeputusanAcara::where(
                    'peserta_id',
                    $pesertaId
                )->exists();

                if (!$masihDigunakan) {
                    Peserta::where('id', $pesertaId)->delete();
                }
            }

        });


        return redirect()
            ->route('merentas_desa.index')
            ->with('success', 'Rekod Rentas Desa berjaya dipadam.');
    }
}
