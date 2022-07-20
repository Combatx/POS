<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('login.index');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'name' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            if (Auth::user()->status == 'Aktif') {

                $request->session()->regenerate();
                return redirect()->intended('/');
            } elseif (Auth::user()->status == 'NonAktif') {
                Auth::logout();
                return back()->with('loginError', 'Akun tidak akif Silahkan hubungi admin');
            };
        }

        return back()->with('loginError', 'Username atau Password Salah');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect(route('login.index'));
    }
}
