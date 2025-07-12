<?php

namespace App\Http\Controllers;

use App\Services\PermissionService;
use App\Services\ResponseService;
use App\Traits\CrudController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    use CrudController;
    public Role $model;
    public $rutaVisita = 'Roles';
    public function __construct()
    {
        $this->model = new Role();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render($this->rutaVisita . '/Index', array_merge([
            'listado' => $this->model::all(),
        ], PermissionService::getPermissions($this->rutaVisita)));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permiso = strtolower($this->rutaVisita);
        if (!Auth::user()->can($permiso.'-create')) {
            abort(403);
        }
        return Inertia::render($this->rutaVisita . '/CreateUpdate', array_merge([
            'isCreate' => true
        ], PermissionService::getPermissions($permiso)));
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $roles)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $roles)
    {
        $permiso = strtolower($this->rutaVisita);
        if (!Auth::user()->can($permiso.'-edit')) {
            abort(403);
        }
        return Inertia::render($this->rutaVisita . '/CreateUpdate', array_merge([
            'isCreate' => false,
            'model' => $roles,
        ], PermissionService::getPermissions($permiso)));
    }

    /**
     * Get all permissions
     */
    public function getAllPermissions()
    {
        try {
            $permissions = Permission::all();
            return ResponseService::success('Permisos obtenidos correctamente', $permissions);
        } catch (\Exception $e) {
            return ResponseService::error('Error al obtener los permisos', $e->getMessage());
        }
    }

    /**
     * Get permissions for a specific role
     */
    public function getRolePermissions(Role $role)
    {
        try {
            $rolePermissions = $role->permissions()->pluck('id')->toArray();
            return ResponseService::success('Permisos del rol obtenidos correctamente', $rolePermissions);
        } catch (\Exception $e) {
            return ResponseService::error('Error al obtener los permisos del rol', $e->getMessage());
        }
    }

    /**
     * Assign permissions to a role
     */
    public function assignPermissions(Request $request, Role $role)
    {
        try {
            $permissions = $request->input('permissions', []);
            $role->permissions()->sync($permissions);
            return ResponseService::success('Permisos asignados correctamente', $role->permissions);
        } catch (\Exception $e) {
            return ResponseService::error('Error al asignar los permisos', $e->getMessage());
        }
    }
}
