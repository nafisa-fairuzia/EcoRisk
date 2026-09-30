<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegistrationForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'alamat' => ['required', 'string', 'max:255'],
            'rt' => ['required', 'string', 'max:5'],
            'rw' => ['required', 'string', 'max:5'],
            'telepon' => ['nullable', 'string', 'max:20'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'alamat' => $validated['alamat'],
            'role' => 'warga',
            'zona_wilayah' => "RT {$validated['rt']} / RW {$validated['rw']}",
            'telepon' => $validated['telepon'] ?? null,
            'status' => 'Aktif',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('warga.dashboard')->with('success', 'Selamat datang di SiklusSampah! Akun warga Anda berhasil dibuat.');
    }
}
