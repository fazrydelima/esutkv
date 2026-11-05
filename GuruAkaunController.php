<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class GuruAkaunController extends Controller
{
    public function index()
    {
        $akaunGuru = User::with('rumahSukan')
            ->where('role', 'guru')
            ->orderBy('rumah_sukan_id')
            ->get();

        return view('esutkv.guru.akaun', compact('akaunGuru'));
    }

    public function updatePassword(Request $request, User $user)
    {
        abort_unless($user->role === 'guru', 404);

        $data = $request->validate([
            'password' => 'required|string|min:12|confirmed',
        ]);

        $user->password = $data['password'];
        $user->save();

        return redirect()
            ->route('admin.guru-accounts.index')
            ->with('success', 'Kata laluan akaun guru berjaya ditetapkan.');
    }
}
