<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\PermissionService;
use App\Services\ResponseService;
use App\Traits\CrudController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    use CrudController;
    public User $model;
    public $rutaVisita = 'User';
    public function __construct()
    {
        $this->model = new User();
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
        /*if (!Auth::user()->can($permiso.'-create')) {
            abort(403);
        }*/
        return Inertia::render($this->rutaVisita . '/CreateUpdate', array_merge([
            'isCreate' => true
        ], PermissionService::getPermissions($permiso)));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $data = $this->model::create($request->all());
            return ResponseService::success('Registro guardado correctamente', $data);
        } catch (\Exception $e) {
            return ResponseService::error('Error al guardar el registro', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $permiso = strtolower($this->rutaVisita);
        /*if (!Auth::user()->can($permiso.'-edit')) {
            abort(403);
        }*/
        return Inertia::render($this->rutaVisita . '/CreateUpdate', array_merge([
            'isCreate' => false,
            'model' => $user,
        ], PermissionService::getPermissions($permiso)));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        try {
            // Check if the request contains a password field
            if ($request->has('password') && !empty($request->password)) {
                // Only allow users to change their own password
                if (Auth::id() !== $user->id) {
                    return ResponseService::error('No autorizado', 'No puede modificar la contraseña de otro usuario');
                }

                // If we're here, the user is changing their own password
                $data = $request->all();
            } else {
                // If no password is being changed, or it's empty, proceed with the update
                // but remove the password field to prevent empty password updates
                $data = $request->except('password');
            }

            $user->update($data);
            return ResponseService::success('Registro actualizado correctamente', $user);
        } catch (\Exception $e) {
            return ResponseService::error('Error al actualizar el registro', $e->getMessage());
        }
    }
    /**
     * Get all roles
     */
    public function getAllRoles()
    {
        try {
            $roles = Role::all();
            return ResponseService::success('Roles obtenidos correctamente', $roles);
        } catch (\Exception $e) {
            return ResponseService::error('Error al obtener los roles', $e->getMessage());
        }
    }

    /**
     * Get roles for a specific user
     */
    public function getUserRoles(User $user)
    {
        try {
            $userRoles = $user->roles()->pluck('id')->toArray();
            return ResponseService::success('Roles del usuario obtenidos correctamente', $userRoles);
        } catch (\Exception $e) {
            return ResponseService::error('Error al obtener los roles del usuario', $e->getMessage());
        }
    }

    /**
     * Assign roles to a user
     */
    public function assignRoles(Request $request, User $user)
    {
        try {
            $roles = $request->input('roles', []);
            $user->roles()->sync($roles);
            return ResponseService::success('Roles asignados correctamente', $user->roles);
        } catch (\Exception $e) {
            return ResponseService::error('Error al asignar los roles', $e->getMessage());
        }
    }
}
