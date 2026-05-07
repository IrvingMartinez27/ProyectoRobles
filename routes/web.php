<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\VentaController;
use App\Http\Controllers\Admin\ProductoController;

/*
|--------------------------------------------------------------------------
| RUTAS PRINCIPALES
|--------------------------------------------------------------------------
*/

// Redirige la raiz del sitio al login
Route::get('/', function () {
    return redirect('/login');
});

// Muestra el dashboard con los datos de ventas, top productos y stock bajo
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Muestra la vista de ventas del dia con la lista de clientes y el ticket
Route::get('/sales', [VentaController::class, 'index'])->name('sales');

// Registra una nueva venta, si el cliente no existe lo crea automaticamente
Route::post('/ventas', [VentaController::class, 'store'])->name('ventas.store');
Route::put('/ventas/{id}', [VentaController::class, 'update'])->name('ventas.update');
Route::delete('/ventas/{id}', [VentaController::class, 'destroy'])->name('ventas.destroy');

// Muestra el catalogo de productos con carrito de ventas
// Pasa los clientes para el autocomplete del carrito
Route::get('/catalog', function () {
    return view('catalog', [
        'clientes'  => \App\Models\Client::all(),
        'productos' => []
    ]);
})->name('catalog');

// Muestra el formulario de login
Route::get('/login', function () {
    return view('login');
})->name('login');

// Procesa el formulario de login, verifica las credenciales
// Si son correctas manda al dashboard, si no regresa con error
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

// Cierra la sesion del usuario, limpia los datos y redirige al login
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

// Muestra el resumen diario de ventas con filtro por fecha
// Acepta GET para el filtro de fecha y POST desde el boton del dashboard
Route::match(['GET', 'POST'], '/resumen', function () {
    return view('resumen');
})->name('resumen');

// Muestra el reporte completo de ventas con filtro por rango de fechas
Route::get('/reporte', function () {
    return view('reporte');
})->name('reporte');

// Procesa la accion de reponer stock de un producto desde el dashboard
Route::post('/reponer', function (Request $request) {
    return back();
})->name('reponer');

// Rutas de productos — CRUD completo en Admin
Route::get('/inventario', [ProductoController::class, 'index'])->name('inventario');
Route::post('/productos', [ProductoController::class, 'store'])->name('productos.store');
Route::get('/productos/{id}', [ProductoController::class, 'show'])->name('productos.show');
Route::put('/productos/{id}', [ProductoController::class, 'update'])->name('productos.update');
Route::delete('/productos/{id}', [ProductoController::class, 'destroy'])->name('productos.destroy');

// Rutas de clientes — Irving maneja la logica en ClientController
Route::get('/clientes', [ClientController::class, 'index'])->name('clientes');
Route::post('/clientes', [ClientController::class, 'store'])->name('clientes.store');
Route::put('/clientes/{client}', [ClientController::class, 'update'])->name('clientes.update');
Route::delete('/clientes/{client}', [ClientController::class, 'destroy'])->name('clientes.destroy');