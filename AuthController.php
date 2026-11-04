<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
class AuthController extends Controller
{
    public function login(Request $request){
        if(Auth::attempt($request->only('email','password'))){
            return redirect()->route('studentview');
        }
        else{
            return redirect()->back()->with('error','Invalid data');
        }
    }

    public function logout(){
        auth()->logout();
        return redirect()->route('home');
    }
}
