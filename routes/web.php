<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\{
    ClienteController,
    ContratoController,
    ContratoIncidenciasController,
    DashboardController,
    EquipoTrabajoController,
    EquipoServicioController,
    EmpleadoController,
    EmpleadoEquipoTrabajoController,
    EquipoTrabajoServicioController,
    IncidenciasController,
    ProductoController,
    ProductoServicioController,
    ReporteController,
    ServicioController,
    RolesController,
    UserController,
    PermissionsController,
    MenuController
};

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    $resources = [
        'cliente' => ClienteController::class,
        'contrato' => ContratoController::class,
        'contrato-incidencias' => ContratoIncidenciasController::class,
        'empleado' => EmpleadoController::class,
        'equipo-servicio' => EquipoServicioController::class,
        'equipo-trabajo' => EquipoTrabajoController::class,
        'empleado-equipo-trabajo' => EmpleadoEquipoTrabajoController::class,
        'equipo-trabajo-servicio' => EquipoTrabajoServicioController::class,
        'incidencias' => IncidenciasController::class,
        'producto' => ProductoController::class,
        'producto-servicio' => ProductoServicioController::class,
        'servicio' => ServicioController::class,
        'roles' => RolesController::class,
        'permissions' => PermissionsController::class,
        'users' => UserController::class,
        'menu' => MenuController::class,
    ];

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Resource routes
    foreach ($resources as $key => $controller) {
        Route::resource("/$key", $controller);
    }

    // Role and Permission Management Routes
    Route::get('/roles/permissions', [RolesController::class, 'getAllPermissions'])->name('roles.permissions');
    Route::get('/roles/{role}/permissions', [RolesController::class, 'getRolePermissions'])->name('roles.getPermissions');
    Route::post('/roles/{role}/permissions', [RolesController::class, 'assignPermissions'])->name('roles.assignPermissions');

    Route::get('/users/roles', [UserController::class, 'getAllRoles'])->name('users.roles');
    Route::get('/users/{user}/roles', [UserController::class, 'getUserRoles'])->name('users.getRoles');
    Route::post('/users/{user}/roles', [UserController::class, 'assignRoles'])->name('users.assignRoles');

    // Reports routes
    Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
});


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
