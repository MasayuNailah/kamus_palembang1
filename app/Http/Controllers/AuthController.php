<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('pages.Login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required','string'],
            'password' => ['required','string'],
        ]);

        // Attempt to authenticate using username
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirect based on role: kontributor -> /beranda, validator/admin -> /EntriKata
            $role = strtolower(Auth::user()->role ?? '');
            if ($role === 'kontributor') {
                $default = '/beranda';
            } elseif ($role === 'validator' || $role === 'admin') {
                $default = '/EntriKata';
            } else {
                $default = '/beranda';
            }

            return redirect()->intended($default);
        }

        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
