<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Services\ResponseService;
use App\Traits\CrudController;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class EmpleadoController extends Controller
{
    use CrudController;
    public Empleado $model;
    public $rutaVisita = 'Empleado';
    public function __construct()
    {
        $this->model = new Empleado();
    }

    /**
     * Get roles for a specific employee
     */
    public function getEmployeeRoles(Empleado $empleado)
    {
        try {
            $employeeRoles = $empleado->roles()->pluck('id')->toArray();
            return ResponseService::success('Roles del empleado obtenidos correctamente', $employeeRoles);
        } catch (\Exception $e) {
            return ResponseService::error('Error al obtener los roles del empleado', $e->getMessage());
        }
    }

    /**
     * Assign roles to an employee
     */
    public function assignRoles(Request $request, Empleado $empleado)
    {
        try {
            $roles = $request->input('roles', []);
            $empleado->roles()->sync($roles);
            return ResponseService::success('Roles asignados correctamente', $empleado->roles);
        } catch (\Exception $e) {
            return ResponseService::error('Error al asignar los roles', $e->getMessage());
        }
    }
}
