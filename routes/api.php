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
    ServicioController,
    MenuController,
    UserController
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
];
/*
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');*/

// Resource routes
foreach ($resources as $key => $controller) {
    Route::post("/$key/query", [$controller, 'query'])->name("api.$key.query");
    Route::post("/$key/store", [$controller, 'store'])->name("api.$key.store");
    Route::put("/$key/{".$key."}", [$controller, 'update'])->name("api.$key.update");
}

// Page visit counter-routes
Route::get('/page-visits', [PageVisitController::class, 'getCount'])->name('api.page-visits.count');
Route::post('/page-visits/increment', [PageVisitController::class, 'increment'])->name('api.page-visits.increment');
