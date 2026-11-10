<?php

namespace App\Http\Controllers;

use App\Models\Acara;
use App\Models\KeputusanAcara;
use App\Models\Peserta;
use App\Models\Rumah_Sukan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KejohananController extends Controller
{
     // Tetapan pingat dan mata
    private function senaraiPingat(): array
    {
        return [
            1 => [
                'pingat' => 'Emas',
                'mata' => 10,
            ],
            2 => [
                'pingat' => 'Perak',
                'mata' => 8,
            ],
            3 => [
                'pingat' => 'Gangsa',
                'mata' => 6,
            ],
        ];
    }

    // Paparan utama kejohanan
    public function show()
    {
        return $this->formAndResults();
    }

    public function create()
    {
        return $this->formAndResults();
    }

    private function formAndResults()
    {
        $acara = Acara::orderBy('nama_acara')->get();

        $peserta = Peserta::orderBy('nama_peserta')->get();

        $kejohanan = KeputusanAcara::with(['acara', 'peserta'])
            ->orderBy('id')
            ->get();

        return view('esutkv.kejohanan.index', compact(
            'kejohanan',
            'acara',
            'peserta'
        ));
    }

    // SIMPAN KEPUTUSAN
    public function store(Request $request)
    {
        $data = $request->validate([
            'acara_id' => 'required|exists:acaras,id',

            'peserta_id' => 'required|array|size:3',

            'peserta_id.1' => 'required|exists:pesertas,id|different:peserta_id.2|different:peserta_id.3',

            'peserta_id.2' => 'required|exists:pesertas,id|different:peserta_id.3',

            'peserta_id.3' => 'required|exists:pesertas,id',
        ]);

        $pingatMengikutKedudukan = $this->senaraiPingat();

        // Semak sama ada keputusan acara sudah direkodkan
        $sudahAda = KeputusanAcara::where('acara_id', $data['acara_id'])
            ->exists();

        if ($sudahAda) {
            return back()
                ->withErrors([
                    'acara_id' => 'Keputusan bagi acara ini telah direkodkan. Sila gunakan fungsi Edit jika ingin membuat perubahan.'
                ])
                ->withInput();
        }

        DB::transaction(function () use (
            $data,
            $pingatMengikutKedudukan,
            $request
        ) {
            foreach ($pingatMengikutKedudukan as $kedudukan => $keputusan) {

                $peserta = Peserta::findOrFail(
                    $data['peserta_id'][$kedudukan]
                );

                KeputusanAcara::create([
                    'acara_id' => $data['acara_id'],

                    'peserta_id' => $peserta->id,

                    'rumah_sukan_id' => $peserta->rumah_sukan_id,

                    'pingat' => $keputusan['pingat'],

                    'mata' => $keputusan['mata'],

                    'status' => 'approved',

                    'submitted_by' => $request->user()->id,

                    'reviewed_by' => $request->user()->id,

                    'reviewed_at' => now(),
                ]);
            }
        });

        return redirect()
            ->route('kejohanan.index')
            ->with('success', 'Keputusan kejohanan berjaya disimpan.');
    }

    // PAPAR BORANG KEMAS KINI
    public function showupdate()
    {
        return $this->formAndResults();
    }

    public function edit(int $id)
    {
        $kejohanan = KeputusanAcara::findOrFail($id);

        $acara = Acara::orderBy('nama_acara')->get();

        $peserta = Peserta::orderBy('nama_peserta')->get();

        return view('esutkv.kejohanan.update', compact(
            'kejohanan',
            'acara',
            'peserta'
        ));
    }

    // KEMAS KINI KEPUTUSAN
    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'acara_id' => 'required|exists:acaras,id',

            'peserta_id' => 'required|exists:pesertas,id',

            'kedudukan' => 'required|integer|in:1,2,3',
        ]);

        $kejohanan = KeputusanAcara::findOrFail($id);

        $pingatMengikutKedudukan = $this->senaraiPingat();

        $keputusan = $pingatMengikutKedudukan[$data['kedudukan']];

        // Semak jika pingat yang sama sudah digunakan dalam acara tersebut
        $pertindihan = KeputusanAcara::where('acara_id', $data['acara_id'])
            ->where('pingat', $keputusan['pingat'])
            ->where('id', '!=', $kejohanan->id)
            ->exists();

        if ($pertindihan) {
            return back()
                ->withErrors([
                    'kedudukan' => 'Pingat tersebut sudah direkodkan untuk acara ini.'
                ])
                ->withInput();
        }

        $peserta = Peserta::findOrFail($data['peserta_id']);

        $kejohanan->update([
            'acara_id' => $data['acara_id'],

            'peserta_id' => $peserta->id,

            'rumah_sukan_id' => $peserta->rumah_sukan_id,

            'pingat' => $keputusan['pingat'],

            // Mata ditentukan secara automatik
            'mata' => $keputusan['mata'],
        ]);

        return redirect()
            ->route('kejohanan.index')
            ->with('success', 'Rekod berjaya dikemaskini.');
    }

    // PADAM KEPUTUSAN
    public function delete(int $id)
    {
        $kejohanan = KeputusanAcara::findOrFail($id);

        $kejohanan->delete();

        return redirect()
            ->route('kejohanan.index')
            ->with('success', 'Rekod berjaya dipadam.');
    }

    public function jumlahMata()
    {
        return redirect()->route('dashboard');
    }

    // DASHBOARD
    public function dashboard()
    {
        $totalPeserta = Peserta::count();

        $totalAcara = Acara::count();

        $totalRumahSukan = Rumah_Sukan::count();

        $keputusans = KeputusanAcara::with([
                'acara',
                'peserta.rumahSukan'
            ])
            ->orderByDesc('id')
            ->take(10)
            ->get();

        $rumahSukans = Rumah_Sukan::withSum(
            ['keputusanAcara as jumlah_mata'],
            'mata'
        )
        ->orderByDesc('jumlah_mata')
        ->get();

        return view('esutkv.dashboard', compact(
            'totalPeserta',
            'totalAcara',
            'totalRumahSukan',
            'keputusans',
            'rumahSukans'
        ));
    }
}
