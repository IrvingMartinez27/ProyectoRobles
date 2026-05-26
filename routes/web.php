<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\VentaController;
use App\Http\Controllers\Admin\ProductoController;
use App\Http\Controllers\Admin\InventarioController;
use App\Http\Controllers\Admin\SaleController;
use App\Http\Controllers\Admin\CatalogController;
use App\Http\Controllers\Admin\ReporteController;
use App\Http\Controllers\Admin\ResumenController;

Route::get('/', function () { return view('welcome'); });

// ── REGISTRO ──────────────────────────────────────────────────────────────

Route::get('/register', function () { return view('register'); })->name('register')->middleware('guest');

Route::post('/register', function (Request $request) {
    $request->validate([
        'name'     => 'required|string|max:255',
        'email'    => 'required|email|unique:central.users,email',
        'password' => 'required|min:8|confirmed',
    ], [
        'name.required'      => 'El nombre es obligatorio.',
        'email.required'     => 'El correo electrónico es obligatorio.',
        'email.email'        => 'Ingresa un correo electrónico válido.',
        'email.unique'       => 'Este correo ya está registrado.',
        'password.required'  => 'La contraseña es obligatoria.',
        'password.min'       => 'La contraseña debe tener al menos 8 caracteres.',
        'password.confirmed' => 'Las contraseñas no coinciden.',
    ]);

    // Crear el usuario en la BD central (sin tienda aún)
    $user = \App\Models\User::create([
        'name'       => $request->name,
        'store_name' => $request->name, // temporal hasta que complete el setup
        'email'      => $request->email,
        'password'   => bcrypt($request->password),
        'estado'     => true,
        'role'       => 'admin',
        'plan'       => 'gratis',
    ]);

    Auth::login($user);
    $request->session()->regenerate();

    // Redirige al setup para que ponga el nombre de su tienda
    return redirect('/setup');
})->name('register.post')->middleware('guest');

// ── SETUP — Nombre de tienda ──────────────────────────────────────────────

Route::get('/setup', function () {
    if (Auth::user()->tenant_id) {
        return redirect('/dashboard');
    }
    return view('setup');
})->name('setup')->middleware('auth');

Route::post('/setup', function (Request $request) {
    $request->validate([
        'store_name' => 'required|string|max:255',
    ], [
        'store_name.required' => 'El nombre de tu tienda es obligatorio.',
    ]);

    // Crear el tenant — genera su BD automáticamente con todas las tablas
    $tenant = \App\Models\Tenant::create([
        'id'         => \Illuminate\Support\Str::uuid(),
        'store_name' => $request->store_name,
        'plan'       => 'gratis',
    ]);

    // Vincular tenant al usuario y actualizar nombre de tienda
    Auth::user()->update([
        'store_name' => $request->store_name,
        'tenant_id'  => $tenant->id,
    ]);

    return redirect('/dashboard');
})->name('setup.post')->middleware('auth');

// ── LOGIN ─────────────────────────────────────────────────────────────────

Route::get('/login', function () { return view('login'); })->name('login')->middleware('guest');

Route::post('/login', function (Request $request) {
    $request->validate([
        'email'    => 'required|email',
        'password' => 'required',
    ], [
        'email.required'    => 'El correo electrónico es obligatorio.',
        'email.email'       => 'Ingresa un correo electrónico válido.',
        'password.required' => 'La contraseña es obligatoria.',
    ]);

    if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
        $request->session()->regenerate();

        // Si no ha completado el setup, mandarlo ahí
        if (!Auth::user()->tenant_id) {
            return redirect('/setup');
        }

        return Auth::user()->role === 'admin'
            ? redirect()->intended('/dashboard')
            : redirect()->intended('/sales');
    }

    return back()->withErrors(['email' => 'El correo o la contraseña son incorrectos.'])->onlyInput('email');
})->name('login.post');

// ── GOOGLE OAUTH ──────────────────────────────────────────────────────────

Route::get('/auth/google', [App\Http\Controllers\Auth\GoogleController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [App\Http\Controllers\Auth\GoogleController::class, 'callback'])->name('google.callback');

// ── LOGOUT ────────────────────────────────────────────────────────────────

Route::get('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');

/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS — Todos los usuarios (admin y vendedor)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    Route::get('/sales', [SaleController::class, 'index'])->name('sales');
    Route::post('/sales', [SaleController::class, 'store'])->name('ventas.store');
    Route::put('/ventas/{id}', [VentaController::class, 'update'])->name('ventas.update');
    Route::delete('/ventas/{id}', [VentaController::class, 'destroy'])->name('ventas.destroy');

    Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog');
    Route::post('/catalog', [CatalogController::class, 'store'])->name('catalog.store');

    Route::get('/inventario', [InventarioController::class, 'index'])->name('inventario');
    Route::post('/inventario/productos', [InventarioController::class, 'store'])->name('productos.store');
    Route::put('/inventario/update', [InventarioController::class, 'update'])->name('inventario.update');

    Route::get('/inicio', function () {
        return Auth::user()->role === 'admin' ? redirect('/dashboard') : redirect('/sales');
    })->name('inicio');

});

/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS — Solo ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'solo.admin'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/productos/{id}', [ProductoController::class, 'show'])->name('productos.show');
    Route::put('/productos/{id}', [ProductoController::class, 'update'])->name('productos.update');
    Route::delete('/productos/{id}', [ProductoController::class, 'destroy'])->name('productos.destroy');

    Route::get('/clientes', [ClientController::class, 'index'])->name('clientes');
    Route::post('/clientes', [ClientController::class, 'store'])->name('clientes.store');
    Route::put('/clientes/{client}', [ClientController::class, 'update'])->name('clientes.update');
    Route::delete('/clientes/{client}', [ClientController::class, 'destroy'])->name('clientes.destroy');

    Route::get('/reporte', [ReporteController::class, 'index'])->name('reporte');
    Route::match(['GET', 'POST'], '/resumen', [ResumenController::class, 'index'])->name('resumen');
    Route::post('/reponer', [DashboardController::class, 'reponer'])->name('reponer');

    // ── USUARIOS con restricción plan gratis ──────────────────────
    Route::get('/usuarios', function () {
        $usuarios = \App\Models\User::where('id', '!=', Auth::id())
                        ->where('tenant_id', Auth::user()->tenant_id)
                        ->get();
        $plan = Auth::user()->plan ?? 'gratis';
        return view('usuarios', compact('usuarios', 'plan'));
    })->name('usuarios');

    Route::post('/usuarios', function (Request $request) {
        $plan          = Auth::user()->plan ?? 'gratis';
        $totalUsuarios = \App\Models\User::where('id', '!=', Auth::id())
                            ->where('tenant_id', Auth::user()->tenant_id)
                            ->count();

        if ($plan === 'gratis' && $totalUsuarios >= 1) {
            return back()
                ->with('limite_usuarios', true)
                ->with('error', 'El plan Gratis solo permite 1 usuario. Actualiza a Pro para agregar vendedores ilimitados.');
        }

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:central.users,email',
            'password' => 'required|min:8',
            'role'     => 'required|in:admin,vendedor',
        ], [
            'name.required'     => 'El nombre es obligatorio.',
            'email.required'    => 'El correo es obligatorio.',
            'email.unique'      => 'Este correo ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min'      => 'La contraseña debe tener al menos 8 caracteres.',
            'role.required'     => 'El rol es obligatorio.',
        ]);

        \App\Models\User::create([
            'name'       => $request->name,
            'store_name' => Auth::user()->store_name,
            'email'      => $request->email,
            'password'   => bcrypt($request->password),
            'estado'     => true,
            'role'       => $request->role,
            'plan'       => 'gratis',
            'tenant_id'  => Auth::user()->tenant_id,
        ]);

        return back()->with('success', 'Usuario creado correctamente.');
    })->name('usuarios.store');

    Route::delete('/usuarios/{id}', function ($id) {
        $usuario = \App\Models\User::findOrFail($id);
        if ($usuario->id === Auth::id()) {
            return back()->with('error', 'No puedes eliminarte a ti mismo.');
        }
        // Verificar que el usuario pertenece al mismo tenant
        if ($usuario->tenant_id !== Auth::user()->tenant_id) {
            return back()->with('error', 'No tienes permiso para eliminar este usuario.');
        }
        $usuario->delete();
        return back()->with('success', 'Usuario eliminado.');
    })->name('usuarios.destroy');

});