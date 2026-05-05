<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| RUTAS PRINCIPALES
|--------------------------------------------------------------------------
*/

// Redirige la raiz del sitio al login, si entras a "/" te manda a "/login"
Route::get('/', function () {
    return redirect('/login');
});

// Muestra el dashboard con los datos de ventas, top productos y stock bajo
// Esta ruta usa el DashboardController que maneja la logica y pasa los datos a la vista
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Muestra la vista de ventas del dia con la lista de clientes y el ticket
Route::get('/sales', function () {
    return view('sales');
})->name('sales');

// Muestra el catalogo de productos con filtros por categoria
Route::get('/catalog', function () {
    return view('catalog');
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
    return view('reponer');
})->name('reponer');

// Muestra el inventario completo con todas las tallas y existencias de cada producto
// Por ahora manda un arreglo vacio, tu companero lo reemplaza con la query real
Route::get('/inventario', function () {
    return view('inventario', [
        'productos' => []
    ]);
})->name('inventario');

// Muestra la lista de clientes con top compradores e historial de compras
// Por ahora manda arreglos vacios, tu companero los reemplaza con las queries reales
Route::get('/clientes', fn() => view('clientes', [
    'clientes'       => [],
    'topCompradores' => [],
    'nuevosEsteMes'  => 0
]))->name('clientes');