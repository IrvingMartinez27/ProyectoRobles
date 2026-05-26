<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    // Redirige a Google
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    // Google regresa aquí con los datos del usuario
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect('/login')->withErrors(['email' => 'No se pudo autenticar con Google. Intenta de nuevo.']);
        }

        // Buscar si ya existe el usuario por email
        $user = User::where('email', $googleUser->getEmail())->first();

        if ($user) {
            // Ya existe — iniciar sesión
            Auth::login($user);
        } else {
            // No existe — crear cuenta nueva con plan gratis
            $user = User::create([
                'name'       => $googleUser->getName(),
                'store_name' => $googleUser->getName() . ' — Tienda',
                'email'      => $googleUser->getEmail(),
                'password'   => bcrypt(\Illuminate\Support\Str::random(24)),
                'estado'     => true,
                'role'       => 'admin',
                'plan'       => 'gratis',
            ]);
            Auth::login($user);
        }

        request()->session()->regenerate();

        return $user->role === 'admin'
            ? redirect('/dashboard')
            : redirect('/sales');
    }
}