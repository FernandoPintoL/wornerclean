<?php

namespace App\Http\Controllers;

use App\Models\Contrato;
use App\Models\ContratoIncidencias;
use App\Models\Producto;
use App\Models\EquipoTrabajo;
use App\Models\EquipoTrabajoServicio;
use App\Services\ResponseService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();

            // Verificar permisos
            if (!$user->hasRole('Super Admin') && !$user->can('reporte-view')) {
                abort(403, 'No tienes permiso para acceder a esta sección');
            }

            return Inertia::render('Reporte/Index');
        } catch (\Exception $e) {
            return ResponseService::error('Error al cargar la página de reportes', $e->getMessage());
        }
    }

    public function reporteContratos(Request $request)
    {
        try {
            $user = Auth::user();

            // Verificar permisos
            if (!$user->hasRole('Super Admin') && !$user->can('reporte-view')) {
                abort(403, 'No tienes permiso para acceder a esta sección');
            }

            $fechaInicio = $request->get('fechaInicio');
            $fechaFin = $request->get('fechaFin');
            $estado = $request->get('estado');
            $estadoPago = $request->get('estadoPago');

            $query = Contrato::query()
                ->with('cliente');

            // Filtrar por fecha
            if ($fechaInicio && $fechaFin) {
                $query->whereBetween('created_at', [
                    "$fechaInicio 00:00:00",
                    "$fechaFin 23:59:59"
                ]);
            }

            // Filtrar por estado
            if ($estado) {
                $query->where('estado', $estado);
            }

            // Filtrar por estado de pago
            if ($estadoPago) {
                $query->where('estado_pago', $estadoPago);
            }

            // Si es cliente, solo ver sus contratos
            if ($user->hasRole('Cliente')) {
                $query->where('cliente_id', $user->id);
            }

            $contratos = $query->get();

            // Calcular estadísticas
            $estadisticas = [
                'total' => $contratos->count(),
                'activos' => $contratos->where('estado', 'activo')->count(),
                'inactivos' => $contratos->where('estado', 'inactivo')->count(),
                'finalizados' => $contratos->where('estado', 'finalizado')->count(),
                'pagados' => $contratos->where('estado_pago', 'pagado')->count(),
                'pendientes' => $contratos->where('estado_pago', 'pendiente')->count(),
                'parciales' => $contratos->where('estado_pago', 'parcial')->count(),
                'montoTotal' => $contratos->sum('monto'),
            ];

            return ResponseService::success('Reporte de contratos generado correctamente', [
                'contratos' => $contratos,
                'estadisticas' => $estadisticas
            ]);
        } catch (\Exception $e) {
            return ResponseService::error('Error al generar el reporte de contratos', $e->getMessage());
        }
    }

    public function reporteIncidencias(Request $request)
    {
        try {
            $user = Auth::user();

            // Verificar permisos
            if (!$user->hasRole('Super Admin') && !$user->can('reporte-view')) {
                abort(403, 'No tienes permiso para acceder a esta sección');
            }

            $fechaInicio = $request->get('fechaInicio');
            $fechaFin = $request->get('fechaFin');
            $estado = $request->get('estado');
            $contratoId = $request->get('contratoId');

            $query = ContratoIncidencias::query()
                ->with(['contrato', 'contrato.cliente']);

            // Filtrar por fecha
            if ($fechaInicio && $fechaFin) {
                $query->whereBetween('created_at', [
                    "$fechaInicio 00:00:00",
                    "$fechaFin 23:59:59"
                ]);
            }

            // Filtrar por estado
            if ($estado) {
                $query->where('estado', $estado);
            }

            // Filtrar por contrato
            if ($contratoId) {
                $query->where('contrato_id', $contratoId);
            }

            // Si es cliente, solo ver incidencias de sus contratos
            if ($user->hasRole('Cliente')) {
                $query->whereHas('contrato', function($q) use ($user) {
                    $q->where('cliente_id', $user->id);
                });
            }

            // Si es líder de equipo, solo ver incidencias asignadas a su equipo
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

                        $query->whereHas('contrato', function($q) use ($equiposIds) {
                            $q->whereHas('equipoTrabajoServicios', function($q2) use ($equiposIds) {
                                $q2->whereIn('equipo_trabajo_id', $equiposIds);
                            });
                        });
                    } else {
                        abort(403, 'No tienes permiso para acceder a esta sección');
                    }
                }
            }

            $incidencias = $query->get();

            // Calcular estadísticas
            $estadisticas = [
                'total' => $incidencias->count(),
                'pendientes' => $incidencias->where('estado', 'pendiente')->count(),
                'enProceso' => $incidencias->where('estado', 'en_proceso')->count(),
                'resueltas' => $incidencias->where('estado', 'resuelta')->count(),
                'cerradas' => $incidencias->where('estado', 'cerrada')->count(),
            ];

            return ResponseService::success('Reporte de incidencias generado correctamente', [
                'incidencias' => $incidencias,
                'estadisticas' => $estadisticas
            ]);
        } catch (\Exception $e) {
            return ResponseService::error('Error al generar el reporte de incidencias', $e->getMessage());
        }
    }

    public function reporteProductos(Request $request)
    {
        try {
            $user = Auth::user();

            // Verificar permisos
            if (!$user->hasRole('Super Admin') && !$user->can('reporte-view')) {
                abort(403, 'No tienes permiso para acceder a esta sección');
            }

            $fechaInicio = $request->get('fechaInicio');
            $fechaFin = $request->get('fechaFin');
            $categoria = $request->get('categoria');

            $query = Producto::query();

            // Filtrar por fecha
            if ($fechaInicio && $fechaFin) {
                $query->whereBetween('created_at', [
                    "$fechaInicio 00:00:00",
                    "$fechaFin 23:59:59"
                ]);
            }

            // Filtrar por categoría
            if ($categoria) {
                $query->where('categoria', $categoria);
            }

            $productos = $query->get();

            // Calcular estadísticas
            $estadisticas = [
                'total' => $productos->count(),
                'totalStock' => $productos->sum('stock'),
                'valorTotal' => $productos->sum(DB::raw('precio * stock')),
                'categorias' => $productos->groupBy('categoria')->map->count(),
            ];

            return ResponseService::success('Reporte de productos generado correctamente', [
                'productos' => $productos,
                'estadisticas' => $estadisticas
            ]);
        } catch (\Exception $e) {
            return ResponseService::error('Error al generar el reporte de productos', $e->getMessage());
        }
    }

    public function reporteEquiposTrabajo(Request $request)
    {
        try {
            $user = Auth::user();

            // Verificar permisos
            if (!$user->hasRole('Super Admin') && !$user->can('reporte-view')) {
                abort(403, 'No tienes permiso para acceder a esta sección');
            }

            $fechaInicio = $request->get('fechaInicio');
            $fechaFin = $request->get('fechaFin');

            $query = EquipoTrabajo::query()
                ->with(['empleados', 'servicios']);

            // Filtrar por fecha
            if ($fechaInicio && $fechaFin) {
                $query->whereBetween('created_at', [
                    "$fechaInicio 00:00:00",
                    "$fechaFin 23:59:59"
                ]);
            }

            // Si es líder de equipo, solo ver sus equipos
            if ($user->hasRole('Empleado')) {
                $empleado = \App\Models\Empleado::where('user_id', $user->id)->first();
                if ($empleado) {
                    $equiposIds = \App\Models\EmpleadoEquipoTrabajo::where('empleado_id', $empleado->id)
                        ->where('ocupacion', 'LIKE', '%Líder%')
                        ->pluck('equipo_trabajo_id');

                    $query->whereIn('id', $equiposIds);
                }
            }

            $equipos = $query->get();

            // Calcular estadísticas
            $estadisticas = [
                'total' => $equipos->count(),
                'totalEmpleados' => $equipos->sum(function($equipo) {
                    return $equipo->empleados->count();
                }),
                'totalServicios' => $equipos->sum(function($equipo) {
                    return $equipo->servicios->count();
                }),
                'serviciosPorEquipo' => $equipos->mapWithKeys(function($equipo) {
                    return [$equipo->nombre => $equipo->servicios->count()];
                }),
                'empleadosPorEquipo' => $equipos->mapWithKeys(function($equipo) {
                    return [$equipo->nombre => $equipo->empleados->count()];
                }),
            ];

            return ResponseService::success('Reporte de equipos de trabajo generado correctamente', [
                'equipos' => $equipos,
                'estadisticas' => $estadisticas
            ]);
        } catch (\Exception $e) {
            return ResponseService::error('Error al generar el reporte de equipos de trabajo', $e->getMessage());
        }
    }
}
