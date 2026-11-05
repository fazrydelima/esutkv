<?php

namespace App\Http\Controllers;

use App\Models\KeputusanAcara;
use Illuminate\Http\Request;

class GuruKeputusanController extends Controller
{
    public function index()
    {
        $keputusans = KeputusanAcara::with(['acara', 'peserta', 'rumahSukan', 'penghantar'])
            ->where('status', 'pending')
            ->oldest()
            ->get();

        return view('esutkv.guru.semakan', compact('keputusans'));
    }

    public function update(Request $request, KeputusanAcara $keputusanAcara)
    {
        $data = $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        if ($keputusanAcara->status !== 'pending') {
            return redirect()
                ->route('admin.guru-keputusan.index')
                ->with('error', 'Keputusan ini telah disemak.');
        }

        if ($data['status'] === 'approved') {
            $pingatSudahDiluluskan = KeputusanAcara::where('acara_id', $keputusanAcara->acara_id)
                ->where('pingat', $keputusanAcara->pingat)
                ->where('status', 'approved')
                ->exists();

            if ($pingatSudahDiluluskan) {
                return redirect()
                    ->route('admin.guru-keputusan.index')
                    ->with('error', 'Pingat ini sudah disahkan untuk acara tersebut. Sila tolak keputusan bertindih.');
            }
        }

        $keputusanAcara->update([
            'status' => $data['status'],
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => now(),
        ]);

        return redirect()
            ->route('admin.guru-keputusan.index')
            ->with('success', $data['status'] === 'approved'
                ? 'Keputusan diluluskan dan dimasukkan ke scoreboard.'
                : 'Keputusan ditolak.');
    }
}
