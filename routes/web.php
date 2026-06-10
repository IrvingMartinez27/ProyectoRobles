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

    $planElegido = $request->input('plan', 'gratis');

    $user = \App\Models\User::create([
        'name'       => $request->name,
        'store_name' => $request->name,
        'email'      => $request->email,
        'password'   => bcrypt($request->password),
        'estado'     => true,
        'role'       => 'admin',
        'plan'       => 'gratis',
    ]);

    Auth::login($user);
    $request->session()->regenerate();
    $request->session()->flash('bienvenida', true);

    if ($planElegido === 'pro') {
        $request->session()->put('pendiente_plan_pro', true);
    }

    return redirect('/setup');
})->name('register.post')->middleware('guest');

// ── PAGO MERCADO PAGO ─────────────────────────────────────────────────────

Route::get('/pago/planes', [App\Http\Controllers\PagoController::class, 'planes'])->name('planes');

Route::middleware(['auth'])->group(function () {
    Route::get('/pago/crear-preferencia', [App\Http\Controllers\PagoController::class, 'crearPreferencia'])->name('pago.crear');
    Route::get('/pago/exito',    [App\Http\Controllers\PagoController::class, 'exito'])->name('pago.exito');
    Route::get('/pago/fallo',    [App\Http\Controllers\PagoController::class, 'fallo'])->name('pago.fallo');
    Route::get('/pago/pendiente',[App\Http\Controllers\PagoController::class, 'pendiente'])->name('pago.pendiente');
});

Route::post('/pago/webhook', [App\Http\Controllers\PagoController::class, 'webhook'])->name('pago.webhook');
Route::post('/pago/cancelar', [App\Http\Controllers\PagoController::class, 'cancelarSuscripcion'])->name('pago.cancelar')->middleware('auth');
Route::get('/pago/exito-suscripcion', [App\Http\Controllers\PagoController::class, 'exitoSuscripcion'])->name('pago.exito.suscripcion')->middleware('auth');

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
        $request->session()->flash('bienvenida', true);

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

Route::get('/dashboard', function() {
    if (session('pendiente_plan_pro')) {
        return redirect('/pago/crear-preferencia');
    }
    return app(DashboardController::class)->index();
})->middleware(['auth', 'solo.admin'])->name('dashboard');

Route::middleware(['auth', 'solo.admin'])->group(function () {

    Route::post('/dashboard/ia', [App\Http\Controllers\Admin\DashboardController::class, 'ia'])->name('dashboard.ia');

    Route::post('/lealtad/toggle', function (Request $request) {
        $user   = Auth::user();
        $tenant = \App\Models\Tenant::find($user->tenant_id);
        if (!$tenant) return back()->with('error', 'No se encontró la tienda.');
        $nuevoEstado = !$tenant->lealtad_activo;
        $tenant->update(['lealtad_activo' => $nuevoEstado]);
        return back()->with('success', $nuevoEstado ? 'Programa de lealtad activado.' : 'Programa de lealtad desactivado.');
    })->name('lealtad.toggle');

    Route::post('/clientes/{id}/canjear', [App\Http\Controllers\Admin\ClientController::class, 'canjearPuntos'])->name('clientes.canjear');

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
    Route::post('/restock/descartar', [DashboardController::class, 'descartarRestock'])->name('restock.descartar');

    // ---- REPORTES DE VENTAS EXCEL Y PDF ---------
    Route::get('/reporte/pdf',   [ReporteController::class, 'exportPdf'])->name('reporte.pdf');
    Route::get('/reporte/excel', [ReporteController::class, 'exportExcel'])->name('reporte.excel');

    // ── TICKET STUDIO (Business) ──────────────────────────────
    Route::get('/ticket-studio',          [App\Http\Controllers\Admin\TicketController::class, 'studio'])->name('ticket.studio');
    Route::post('/ticket-studio/config',  [App\Http\Controllers\Admin\TicketController::class, 'guardarConfig'])->name('ticket.config');
    Route::get('/ticket-studio/preview',  [App\Http\Controllers\Admin\TicketController::class, 'preview'])->name('ticket.preview');
    Route::get('/ticket/{ventaId}/pdf',   [App\Http\Controllers\Admin\TicketController::class, 'pdf'])->name('ticket.pdf');

    // ── PARA ENTREGA / EN TRÁNSITO ────────────────────────────
    Route::get('/para-entrega',           [App\Http\Controllers\Admin\TraspasoController::class, 'index'])->name('para.entrega');
    Route::post('/para-entrega/mover',    [App\Http\Controllers\Admin\TraspasoController::class, 'moverATransito'])->name('entrega.mover');
    Route::post('/para-entrega/regresar', [App\Http\Controllers\Admin\TraspasoController::class, 'regresarALocal'])->name('entrega.regresar');
    Route::post('/para-entrega/venta',    [App\Http\Controllers\Admin\TraspasoController::class, 'confirmarVenta'])->name('entrega.venta');

    // ── NOTIFICACIONES ────────────────────────────────────────
    Route::get('/notificaciones',              [App\Http\Controllers\Admin\NotificacionController::class, 'index'])->name('notificaciones.index');
    Route::post('/notificaciones/{id}/leer',   [App\Http\Controllers\Admin\NotificacionController::class, 'leer'])->name('notificaciones.leer');
    Route::post('/notificaciones/leer-todas',  [App\Http\Controllers\Admin\NotificacionController::class, 'leerTodas'])->name('notificaciones.leerTodas');

    // ── ALMACENES (todas las rutas juntas en solo.admin) ──────
    Route::get('/almacenes',          [App\Http\Controllers\Admin\AlmacenController::class, 'index'])->name('almacenes.index');
    Route::get('/almacenes/{id}',     [App\Http\Controllers\Admin\AlmacenController::class, 'show'])->name('almacenes.show');
    Route::post('/almacenes',         [App\Http\Controllers\Admin\AlmacenController::class, 'store'])->name('almacenes.store');
    Route::put('/almacenes/{id}',     [App\Http\Controllers\Admin\AlmacenController::class, 'update'])->name('almacenes.update');
    Route::delete('/almacenes/{id}',  [App\Http\Controllers\Admin\AlmacenController::class, 'destroy'])->name('almacenes.destroy');

    // ── GASTOS ────────────────────────────────────────────────
    Route::get('/gastos',             [App\Http\Controllers\Admin\GastoOperativoController::class, 'index'])->name('gastos.index');
    Route::post('/gastos',            [App\Http\Controllers\Admin\GastoOperativoController::class, 'store'])->name('gastos.store');
    Route::delete('/gastos/{id}',     [App\Http\Controllers\Admin\GastoOperativoController::class, 'destroy'])->name('gastos.destroy');

    // ── RENTABILIDAD ──────────────────────────────────────────
    Route::get('/rentabilidad',       [App\Http\Controllers\Admin\RentabilidadController::class, 'index'])->name('rentabilidad.index');

    // ── CHAT IA ───────────────────────────────────────────────
    Route::get('/chat-ia',            [App\Http\Controllers\Admin\ChatIAController::class, 'index'])->name('chat.ia.index');
    Route::post('/chat-ia/mensaje',   [App\Http\Controllers\Admin\ChatIAController::class, 'mensaje'])->name('chat.ia.mensaje');

    // ── USUARIOS ──────────────────────────────────────────────
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

        if ($plan === 'gratis' && $totalUsuarios >= 0) {
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
        if ($usuario->tenant_id !== Auth::user()->tenant_id) {
            return back()->with('error', 'No tienes permiso para eliminar este usuario.');
        }
        $usuario->delete();
        return back()->with('success', 'Usuario eliminado.');
    })->name('usuarios.destroy');

});

Route::post('/ventas/voz', [App\Http\Controllers\Admin\SaleController::class, 'voz'])->name('ventas.voz');

// ── SETUP ─────────────────────────────────────────────────────
Route::get('/setup', function () {
    if (Auth::user()->tenant_id) {
        return redirect('/dashboard');
    }
    return view('setup');
})->name('setup')->middleware('auth');

Route::post('/setup', function (Request $request) {
    $request->validate([
        'store_name' => 'required|string|max:255',
        'timezone'   => 'required|string',
    ]);

    $tenant = \App\Models\Tenant::create([
        'id'         => \Illuminate\Support\Str::uuid(),
        'store_name' => $request->store_name,
        'plan'       => 'gratis',
        'timezone'   => $request->timezone ?? 'America/Mexico_City',
    ]);

    Auth::user()->update([
        'store_name' => $request->store_name,
        'tenant_id'  => $tenant->id,
    ]);

    if (session('pendiente_plan_pro')) {
        session()->forget('pendiente_plan_pro');
        return redirect('/pago/crear-preferencia');
    }

    return redirect('/dashboard');
})->name('setup.post')->middleware('auth');

// ── CATÁLOGO PÚBLICO ──────────────────────────────────────────
Route::get('/catalogo/{tenant_id}', [App\Http\Controllers\CatalogoPublicoController::class, 'index'])
    ->name('catalogo.publico');