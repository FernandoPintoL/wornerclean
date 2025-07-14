<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\EquipoTrabajo;
use App\Models\EmpleadoEquipoTrabajo;
use App\Services\ResponseService;
use App\Traits\CrudController;
use Illuminate\Http\Request;

class EmpleadoEquipoTrabajoController extends Controller
{
    use CrudController;
    public EmpleadoEquipoTrabajo $model;
    public $rutaVisita = 'EmpleadoEquipoTrabajo';
    public function __construct()
    {
        $this->model = new EmpleadoEquipoTrabajo();
    }

    /**
     * Get all employees for a specific work team
     */
    public function getEmpleadosByEquipoTrabajo($equipoTrabajoId)
    {
        try {
            $equipoTrabajo = EquipoTrabajo::find($equipoTrabajoId);
            if (!$equipoTrabajo) {
                return ResponseService::error('Equipo de trabajo no encontrado', '', 404);
            }

            $empleados = $equipoTrabajo->empleados()->with('equiposTrabajo')->get();
            return ResponseService::success('Empleados obtenidos correctamente', $empleados);
        } catch (\Exception $e) {
            return ResponseService::error('Error al obtener los empleados', $e->getMessage());
        }
    }

    /**
     * Get all work teams for a specific employee
     */
    public function getEquipoTrabajosByEmpleado($empleadoId)
    {
        try {
            $empleado = Empleado::find($empleadoId);
            if (!$empleado) {
                return ResponseService::error('Empleado no encontrado', '', 404);
            }

            $equiposTrabajo = $empleado->equiposTrabajo()->with('empleados')->get();
            return ResponseService::success('Equipos de trabajo obtenidos correctamente', $equiposTrabajo);
        } catch (\Exception $e) {
            return ResponseService::error('Error al obtener los equipos de trabajo', $e->getMessage());
        }
    }

    /**
     * Assign an employee to a work team
     */
    public function assignEmpleadoToEquipoTrabajo(Request $request)
    {
        try {
            $request->validate([
                'empleado_id' => 'required|exists:empleados,id',
                'equipo_trabajo_id' => 'required|exists:equipo_trabajos,id',
                'ocupacion' => 'required|string',
                'estado' => 'required|string'
            ]);

            // Check if the assignment already exists
            $existingAssignment = EmpleadoEquipoTrabajo::where('empleado_id', $request->empleado_id)
                ->where('equipo_trabajo_id', $request->equipo_trabajo_id)
                ->first();

            if ($existingAssignment) {
                // Update the existing assignment
                $existingAssignment->update([
                    'ocupacion' => $request->ocupacion,
                    'estado' => $request->estado
                ]);
                return ResponseService::success('Asignación actualizada correctamente', $existingAssignment);
            }

            // Create a new assignment
            $assignment = EmpleadoEquipoTrabajo::create([
                'empleado_id' => $request->empleado_id,
                'equipo_trabajo_id' => $request->equipo_trabajo_id,
                'ocupacion' => $request->ocupacion,
                'estado' => $request->estado
            ]);

            return ResponseService::success('Empleado asignado correctamente al equipo de trabajo', $assignment);
        } catch (\Exception $e) {
            return ResponseService::error('Error al asignar el empleado al equipo de trabajo', $e->getMessage());
        }
    }

    /**
     * Remove an employee from a work team
     */
    public function removeEmpleadoFromEquipoTrabajo($id)
    {
        try {
            $assignment = EmpleadoEquipoTrabajo::find($id);
            if (!$assignment) {
                return ResponseService::error('Asignación no encontrada', '', 404);
            }

            $assignment->delete();
            return ResponseService::success('Empleado removido correctamente del equipo de trabajo');
        } catch (\Exception $e) {
            return ResponseService::error('Error al remover el empleado del equipo de trabajo', $e->getMessage());
        }
    }
}
