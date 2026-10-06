<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'Email atau kata sandi tidak cocok.',
            ])->withInput($request->only('email', '_auth_form'));
        }

        $request->session()->regenerate();
        $user = Auth::user();

        if ($user->status !== 'Aktif') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'email' => 'Akun Anda sedang dinonaktifkan oleh administrator.',
            ])->withInput($request->only('email', '_auth_form'));
        }

        return $this->redirectBasedOnRole($user);
    }

    protected function redirectBasedOnRole(User $user): RedirectResponse
    {
        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard')->with('success', 'Selamat datang Administrator, '.$user->name),
            'petugas' => redirect()->route('petugas.dashboard')->with('success', 'Selamat bertugas, '.$user->name),
            'warga' => redirect()->route('warga.dashboard')->with('success', 'Selamat datang, '.$user->name),
            default => redirect('/'),
        };
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda telah berhasil keluar sistem.');
    }
}
