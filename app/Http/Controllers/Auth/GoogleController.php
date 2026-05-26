<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    // Redirige a Google para autenticación
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
            // Ya existe — iniciar sesión directo
            Auth::login($user);
            request()->session()->regenerate();

            // Si nunca completó el setup, mandarlo ahí
            if (!$user->tenant_id) {
                return redirect('/setup');
            }

            return $user->role === 'admin'
                ? redirect('/dashboard')
                : redirect('/sales');
        }

        // No existe — crear cuenta nueva con plan gratis
        $user = User::create([
            'name'       => $googleUser->getName(),
            'store_name' => $googleUser->getName(),
            'email'      => $googleUser->getEmail(),
            'password'   => bcrypt(Str::random(24)),
            'estado'     => true,
            'role'       => 'admin',
            'plan'       => 'gratis',
        ]);

        Auth::login($user);
        request()->session()->regenerate();

        // Redirige al setup para que ponga el nombre de su tienda
        return redirect('/setup');
    }
}