<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ClienteController,
    ContratoController,
    ContratoIncidenciasController,
    EquipoTrabajoController,
    EmpleadoController,
    EmpleadoEquipoTrabajoController,
    EquipoTrabajoServicioController,
    EquipoServicioController,
    IncidenciasController,
    PageVisitController,
    ProductoController,
    ProductoServicioController,
    ReporteController,
    ServicioController,
    MenuController,
    UserController,
    RolesController,
    PermissionsController
};

$resources = [
    'cliente' => ClienteController::class,
    'contrato' => ContratoController::class,
    'contrato-incidencias' => ContratoIncidenciasController::class,
    'equipo-trabajo' => EquipoTrabajoController::class,
    'empleado' => EmpleadoController::class,
    'empleado-equipo-trabajo' => EmpleadoEquipoTrabajoController::class,
    'equipo-trabajo-servicio' => EquipoTrabajoServicioController::class,
    'incidencias' => IncidenciasController::class,
    'producto' => ProductoController::class,
    'producto-servicio' => ProductoServicioController::class,
    'servicio' => ServicioController::class,
    'menu' => MenuController::class,
    'equipo-servicio' => EquipoServicioController::class,
    'users' => UserController::class,
    'roles' => RolesController::class,
    'permissions' => PermissionsController::class,
];

// Resource routes with authentication
Route::middleware(['web', 'auth'])->group(function () use ($resources) {
    foreach ($resources as $key => $controller) {
        Route::post("/$key/query", [$controller, 'query'])->name("api.$key.query");
        Route::post("/$key/store", [$controller, 'store'])->name("api.$key.store");
        Route::put("/$key/{".$key."}", [$controller, 'update'])->name("api.$key.update");
        Route::delete("/$key/{".$key."}", [$controller, 'destroy'])->name("api.$key.destroy");
    }
});

// Public routes (no authentication needed)
Route::get('/menu/main-menus', [MenuController::class, 'getMainMenus'])->name('api.menu.main-menus');
Route::get('/page-visits', [PageVisitController::class, 'getCount'])->name('api.page-visits.count');
Route::get('/page-visits/all', [PageVisitController::class, 'getAllCounts'])->name('api.page-visits.all');
Route::post('/page-visits/increment', [PageVisitController::class, 'increment'])->name('api.page-visits.increment');

// Authenticated routes
Route::middleware(['web', 'auth'])->group(function () {
    // EmpleadoEquipoTrabajo routes
    Route::get('/empleado-equipo-trabajo/equipo/{equipoTrabajoId}/empleados', [EmpleadoEquipoTrabajoController::class, 'getEmpleadosByEquipoTrabajo'])->name('api.empleado-equipo-trabajo.empleados-by-equipo');
    Route::get('/empleado-equipo-trabajo/empleado/{empleadoId}/equipos', [EmpleadoEquipoTrabajoController::class, 'getEquipoTrabajosByEmpleado'])->name('api.empleado-equipo-trabajo.equipos-by-empleado');
    Route::post('/empleado-equipo-trabajo/assign', [EmpleadoEquipoTrabajoController::class, 'assignEmpleadoToEquipoTrabajo'])->name('api.empleado-equipo-trabajo.assign');
    Route::delete('/empleado-equipo-trabajo/remove/{id}', [EmpleadoEquipoTrabajoController::class, 'removeEmpleadoFromEquipoTrabajo'])->name('api.empleado-equipo-trabajo.remove');

    // ContratoIncidencias routes
    Route::get('/contrato-incidencias/contrato/{contratoId}/incidencias', [ContratoIncidenciasController::class, 'getIncidenciasByContrato'])->name('api.contrato-incidencias.incidencias-by-contrato');

    // Role permissions routes
    Route::get('/roles/permissions', [RolesController::class, 'getAllPermissions'])->name('api.roles.permissions');
    Route::get('/roles/{role}/permissions', [RolesController::class, 'getRolePermissions'])->name('api.roles.role-permissions');
    Route::post('/roles/{role}/permissions', [RolesController::class, 'assignPermissions'])->name('api.roles.assign-permissions');

    // Employee roles routes
    Route::get('/empleado/{empleado}/roles', [EmpleadoController::class, 'getEmployeeRoles'])->name('api.empleado.roles');
    Route::post('/empleado/{empleado}/roles', [EmpleadoController::class, 'assignRoles'])->name('api.empleado.assign-roles');

    // Report routes
    Route::get('/reportes', [ReporteController::class, 'index'])->name('api.reportes.index');
    Route::post('/reportes/contratos', [ReporteController::class, 'reporteContratos'])->name('api.reportes.contratos');
    Route::post('/reportes/incidencias', [ReporteController::class, 'reporteIncidencias'])->name('api.reportes.incidencias');
    Route::post('/reportes/productos', [ReporteController::class, 'reporteProductos'])->name('api.reportes.productos');
    Route::post('/reportes/equipos-trabajo', [ReporteController::class, 'reporteEquiposTrabajo'])->name('api.reportes.equipos-trabajo');
});
