<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ClienteController,
    ContratoController,
    ContratoIncidenciasController,
    EmpleadoController,
    EmpleadoEquipoTrabajoController,
    EquipoTrabajoServicioController,
    IncidenciasController,
    ProductoController,
    ProductoServicioController,
    ServicioController,
    MenuController
};

$resources = [
    'cliente' => ClienteController::class,
    'contrato' => ContratoController::class,
    'contrato-incidencias' => ContratoIncidenciasController::class,
    'empleado' => EmpleadoController::class,
    'empleado-equipo-trabajo' => EmpleadoEquipoTrabajoController::class,
    'equipo-trabajo-servicio' => EquipoTrabajoServicioController::class,
    'incidencias' => IncidenciasController::class,
    'producto' => ProductoController::class,
    'producto-servicio' => ProductoServicioController::class,
    'servicio' => ServicioController::class,
    'menu' => MenuController::class,
];

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Resource routes
foreach ($resources as $key => $controller) {
    Route::post("/$key/query", [$controller, 'query'])->name("api.$key.query");
    Route::post("/$key/store", [$controller, 'store'])->name("api.$key.store");
    Route::put("/$key/{".$key."}", [$controller, 'update'])->name("api.$key.update");
}
