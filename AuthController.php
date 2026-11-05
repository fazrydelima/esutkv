<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return $this->redirectForRole();
        }

        return view('esutkv.login');
    }

    public function login(Request $request)
    {
        if (Auth::check()) {
            return $this->redirectForRole();
        }

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials)) {
            return back()
                ->withErrors(['email' => 'Emel atau kata laluan tidak sah.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        return $this->redirectForRole();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function redirectForRole()
    {
        return Auth::user()->role === 'guru'
            ? redirect()->route('guru.dashboard')
            : redirect()->route('dashboard');
    }
}
