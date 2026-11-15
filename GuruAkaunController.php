<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Rumah_Sukan;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class GuruAkaunController extends Controller
{
     // Paparkan senarai akaun guru dan borang tambah akaun
    public function index()
    {
        $akaunGuru = User::with('rumahSukan')
            ->where('role', 'guru')
            ->orderBy('rumah_sukan_id')
            ->get();

        $rumahSukans = Rumah_Sukan::orderBy('nama_rumah')->get();

        return view('esutkv.index_guru', compact(
            'akaunGuru',
            'rumahSukans'
        ));
    }

    // Daftar akaun guru baharu
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',

            'email' => 'required|email|max:255|unique:users,email',

            'rumah_sukan_id' => [
                'required',
                'exists:rumah_sukans,id',
            ],

            'password' => 'required|string|min:12|confirmed',
        ], [
            'rumah_sukan_id.unique' =>
                'Rumah sukan ini sudah mempunyai akaun guru.',

            'email.unique' =>
                'Alamat emel ini sudah digunakan.',

            'password.confirmed' =>
                'Pengesahan kata laluan tidak sepadan.',
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'rumah_sukan_id' => $data['rumah_sukan_id'],
            'password' => $data['password'],
            'role' => 'guru',
        ]);

        return redirect()
            ->route('guru-accounts.index')
            ->with('success', 'Akaun guru berjaya didaftarkan.');
    }

    // Tetapkan semula kata laluan akaun guru
    public function updatePassword(Request $request, User $guru)
    {
        abort_unless($guru->role === 'guru', 404);

        $data = $request->validate([
            'password' => 'required|string|min:12|confirmed',
        ]);

        $guru->update([
            'password' => $data['password'],
        ]);

        return redirect()
            ->route('admin.guru-accounts.index')
            ->with('success', 'Kata laluan guru berjaya dikemaskini.');
    }
}
