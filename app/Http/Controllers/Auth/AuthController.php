<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function loginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ]);

        $login = $request->login;

        // 2. Cari user berdasarkan email atau username
        $user = User::where('email', $login)
            ->orWhere('username', $login)
            ->first();

        // Jika user tidak ada ATAU password salah
        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()
                ->withErrors([
                    'login' => 'Username / Password salah'
                ])
                ->withInput();
        }

        // 5. Login manual
        Auth::login($user, true); // true = remember

        // 6. Regenerate session (ANTI session fixation)
        $request->session()->regenerate();

        // 7. Redirect berdasarkan role
        return redirect()->route('dashboard');
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
