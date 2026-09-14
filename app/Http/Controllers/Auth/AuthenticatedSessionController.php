<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Gunakan format email yang benar.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $credentials['active'] = true;

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors(['email' => 'Email atau kata sandi belum sesuai.']);
        }

        $request->session()->regenerate();

        return Auth::user()->isAdmin()
            ? redirect()->intended(route('dashboard'))
            : redirect()->intended(route('attendance.scan'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->session()->flush();
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
