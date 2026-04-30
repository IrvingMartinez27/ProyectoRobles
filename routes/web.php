<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| RUTAS PRINCIPALES (STITCH LOGIN)
|--------------------------------------------------------------------------
*/

// 👉 REDIRECCIÓN INICIAL
Route::get('/', function () {
    return redirect('/login');
});

// 👉 DASHBOARD
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// 👉 SALES
Route::get('/sales', function () {
    return view('sales');
})->name('sales');

// 👉 CATÁLOGO
Route::get('/catalog', function () {
    return view('catalog');
})->name('catalog');

// 👉 LOGIN (vista)
Route::get('/login', function () {
    return view('login');
})->name('login');

// 👉 LOGIN (POST)
Route::post('/login', function (Request $request) {

    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect('/dashboard');
    }

    return back()->withErrors([
        'email' => 'Credenciales incorrectas',
    ]);

})->name('login.post');

// 👉 LOGOUT
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->name('logout');


// 🔥 RUTAS PARA TU DASHBOARD (IMPORTANTE)

Route::match(['GET', 'POST'], '/resumen', function () {
    return view('resumen');
})->name('resumen');

Route::get('/reporte', function () {
    return view('reporte');
})->name('reporte');

// 👉 REPONER INVENTARIO
Route::post('/reponer', function (Request $request) {
    return view('reponer');
})->name('reponer');