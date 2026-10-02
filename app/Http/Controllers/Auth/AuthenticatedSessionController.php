<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AuthenticatedSessionController extends Controller
{
    /**
     * Show the login page.
     */
    public function create(Request $request)
    {
        if (Auth::check()) {
            return redirect('/privada/productos');
        }

        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'usuario' => 'required_without_all:name,email',
            'name' => 'required_without_all:usuario,email',
            'email' => 'required_without_all:usuario,name|email',
            'password' => 'required',
        ]);

        $login = $request->input('usuario') ?? $request->input('name') ?? $request->input('email');
        $password = $request->input('password');

        // Determinar si es email o name
        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        $credentials = [
            $fieldType => $login,
            'password' => $password,
            'autorizado' => true, // Solo usuarios autorizados pueden entrar
        ];

        if (Auth::guard()->attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('/privada/productos');
        }

        return back()->withErrors([
            'login' => 'Las credenciales proporcionadas no son correctas o la cuenta no está autorizada.',
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        return Inertia::location('/');
    }
}
