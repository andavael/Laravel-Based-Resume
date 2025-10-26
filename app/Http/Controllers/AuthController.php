<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // Show login page
    public function showLogin() {
        return view('auth.login');
    }

    // Handle login
    public function login(Request $request) {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('resume.edit'); // Changed to edit page
        }

        return back()->withErrors([
            'username' => 'Invalid username or password.'
        ])->onlyInput('username');
    }

    // Show registration page
    public function showRegister() {
        return view('auth.register');
    }

    // Handle registration
    public function register(Request $request) {
        $request->validate([
            'sr_code' => ['required', 'regex:/^[0-9]{2}-[0-9]{5}$/', 'unique:users,sr_code'],
            'username' => ['required', 'unique:users,username'],
            'email' => ['required', 'email', 'regex:/@g\.batstate-u\.edu\.ph$/', 'unique:users,email'],
            'password' => ['required', 'min:8', 'confirmed'],
        ], [
            'sr_code.regex' => 'SR Code should follow the format 23-04485.',
            'email.regex' => 'Please use your BatStateU email address.'
        ]);

        User::create([
            'sr_code' => $request->sr_code,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with('success', 'Registration successful! Please log in.');
    }
}