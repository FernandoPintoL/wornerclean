<?php

namespace App\Http\Controllers;

use App\Models\Contrato;
use App\Models\ContratoIncidencias;
use App\Models\Producto;
use App\Models\EquipoTrabajo;
use App\Services\ResponseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Get dashboard data for the authenticated user
     */
    public function getDashboardData()
    {
        try {
            $user = Auth::user();

            // Get contracts statistics
            $contratos = Contrato::query();

            // If user is a client, only show their contracts
            if ($user->hasRole('Cliente')) {
                $contratos->where('cliente_id', $user->id);
            }

            $contratosData = $contratos->get();
            $contratosStats = [
                'total' => $contratosData->count(),
                'activos' => $contratosData->where('estado', 'activo')->count(),
                'inactivos' => $contratosData->where('estado', 'inactivo')->count(),
                'finalizados' => $contratosData->where('estado', 'finalizado')->count(),
                'pagados' => $contratosData->where('estado_pago', 'pagado')->count(),
                'pendientes' => $contratosData->where('estado_pago', 'pendiente')->count(),
                'montoTotal' => $contratosData->sum('monto'),
            ];

            // Get incidents statistics
            $incidencias = ContratoIncidencias::query();

            // If user is a client, only show incidents from their contracts
            if ($user->hasRole('Cliente')) {
                $incidencias->whereHas('contrato', function($q) use ($user) {
                    $q->where('cliente_id', $user->id);
                });
            }

            // If user is a team leader, only show incidents assigned to their team
            if ($user->hasRole('Empleado')) {
                $empleado = \App\Models\Empleado::where('user_id', $user->id)->first();
                if ($empleado) {
                    $esLider = \App\Models\EmpleadoEquipoTrabajo::where('empleado_id', $empleado->id)
                        ->where('ocupacion', 'LIKE', '%Líder%')
                        ->exists();

                    if ($esLider) {
                        $equiposIds = \App\Models\EmpleadoEquipoTrabajo::where('empleado_id', $empleado->id)
                            ->where('ocupacion', 'LIKE', '%Líder%')
                            ->pluck('equipo_trabajo_id');

                        $incidencias->whereHas('contrato', function($q) use ($equiposIds) {
                            $q->whereHas('equipoTrabajoServicios', function($q2) use ($equiposIds) {
                                $q2->whereIn('equipo_trabajo_id', $equiposIds);
                            });
                        });
                    }
                }
            }

            $incidenciasData = $incidencias->get();
            $incidenciasStats = [
                'total' => $incidenciasData->count(),
                'pendientes' => $incidenciasData->where('estado', 'pendiente')->count(),
                'enProceso' => $incidenciasData->where('estado', 'en_proceso')->count(),
                'resueltas' => $incidenciasData->where('estado', 'resuelta')->count(),
            ];

            // Get products statistics (only for admin and authorized users)
            $productosStats = [];
            if ($user->hasRole('Super Admin') || $user->can('producto-view')) {
                $productosData = Producto::all();
                $productosStats = [
                    'total' => $productosData->count(),
                    'totalStock' => $productosData->sum('stock'),
                    'valorTotal' => $productosData->sum(function($producto) {
                        return $producto->precio * $producto->stock;
                    }),
                    'bajoStock' => $productosData->where('stock', '<', 10)->count(),
                ];
            }

            // Get work teams statistics
            $equiposStats = [];
            $equiposQuery = EquipoTrabajo::query();

            // If user is a team leader, only show their teams
            if ($user->hasRole('Empleado')) {
                $empleado = \App\Models\Empleado::where('user_id', $user->id)->first();
                if ($empleado) {
                    $equiposIds = \App\Models\EmpleadoEquipoTrabajo::where('empleado_id', $empleado->id)
                        ->where('ocupacion', 'LIKE', '%Líder%')
                        ->pluck('equipo_trabajo_id');

                    $equiposQuery->whereIn('id', $equiposIds);
                }
            }

            if ($user->hasRole('Super Admin') || $user->can('equipo-view') || $user->hasRole('Empleado')) {
                $equiposData = $equiposQuery->with(['empleados', 'servicios'])->get();
                $equiposStats = [
                    'total' => $equiposData->count(),
                    'totalEmpleados' => $equiposData->sum(function($equipo) {
                        return $equipo->empleados->count();
                    }),
                    'totalServicios' => $equiposData->sum(function($equipo) {
                        return $equipo->servicios->count();
                    }),
                ];
            }

            // Return all dashboard data
            return [
                'contratos' => $contratosStats,
                'incidencias' => $incidenciasStats,
                'productos' => $productosStats,
                'equipos' => $equiposStats,
            ];
        } catch (\Exception $e) {
            return ResponseService::error('Error al cargar datos del dashboard', $e->getMessage());
        }
    }

    /**
     * Provide dashboard data to the dashboard view
     */
    public function index()
    {
        try {
            $dashboardData = $this->getDashboardData();

            return Inertia::render('Dashboard', [
                'dashboardData' => $dashboardData
            ]);
        } catch (\Exception $e) {
            return ResponseService::error('Error al cargar el dashboard', $e->getMessage());
        }
    }
}
