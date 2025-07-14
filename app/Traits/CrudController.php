<?php

namespace App\Traits;

use App\Services\PermissionService;
use App\Services\ResponseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

trait CrudController
{
    /**
     * Get the authenticated user safely
     */
    protected function getAuthenticatedUser()
    {
        $user = Auth::user();
        if (!$user) {
            throw new \Exception('Usuario no autenticado');
        }
        return $user;
    }

    protected function getModelName()
    {
        if (!isset($this->rutaVisita)) {
            throw new \Exception('La propiedad $rutaVisita no está definida en el controlador');
        }
        return $this->rutaVisita;
    }

    public function index()
    {
        try{
            $permiso = strtolower($this->getModelName());
            $user = Auth::user();

            // Super Admin puede hacer todo
            if ($user->hasRole('Super Admin')) {
                return Inertia::render($this->getModelName() . '/Index', array_merge([
                    'listado' => $this->model::all(),
                ], PermissionService::getPermissions($permiso)));
            }

            // Cliente solo puede ver contratos e incidencias
            if ($user->hasRole('Cliente')) {
                if ($permiso === 'contrato' || $permiso === 'contratoincidencias') {
                    return Inertia::render($this->getModelName() . '/Index', array_merge([
                        'listado' => $permiso === 'contrato'
                            ? $this->model::where('cliente_id', $user->id)->get()
                            : $this->model::all(),
                    ], PermissionService::getPermissions($permiso)));
                }
                abort(403, 'No tienes permiso para acceder a esta sección');
            }

            // Empleado con rol de líder puede ver incidencias de contratos
            if ($user->hasRole('Empleado')) {
                $empleado = \App\Models\Empleado::where('user_id', $user->id)->first();
                if ($empleado) {
                    $esLider = \App\Models\EmpleadoEquipoTrabajo::where('empleado_id', $empleado->id)
                        ->where('ocupacion', 'LIKE', '%Líder%')
                        ->exists();

                    if ($esLider && $permiso === 'contratoincidencias') {
                        return Inertia::render($this->getModelName() . '/Index', array_merge([
                            'listado' => $this->model::all(),
                        ], PermissionService::getPermissions($permiso)));
                    }
                }
            }

            // Para otros roles, verificar permisos normalmente
            if (!$user->can($permiso . '-view')) {
                abort(403, 'No tienes permiso para acceder a esta sección');
            }

            return Inertia::render($this->getModelName() . '/Index', array_merge([
                'listado' => $this->model::all(),
            ], PermissionService::getPermissions($permiso)));
        } catch (\Exception $e) {
            return ResponseService::error('Error al cargar los datos', $e->getMessage());
        }
    }

    public function create()
    {
        try{
            $permiso = strtolower($this->getModelName());
            $user = $this->getAuthenticatedUser();

            // Super Admin puede hacer todo
            if ($user->hasRole('Super Admin')) {
                return Inertia::render($this->getModelName() . '/CreateUpdate', array_merge([
                    'isCreate' => true
                ], PermissionService::getPermissions($permiso)));
            }

            // Cliente solo puede crear contratos e incidencias
            if ($user->hasRole('Cliente')) {
                if ($permiso === 'contrato' || $permiso === 'contratoincidencias') {
                    return Inertia::render($this->getModelName() . '/CreateUpdate', array_merge([
                        'isCreate' => true
                    ], PermissionService::getPermissions($permiso)));
                }
                abort(403, 'No tienes permiso para acceder a esta sección');
            }

            // Empleado con rol de líder puede crear incidencias de contratos
            if ($user->hasRole('Empleado')) {
                $empleado = \App\Models\Empleado::where('user_id', $user->id)->first();
                if ($empleado) {
                    $esLider = \App\Models\EmpleadoEquipoTrabajo::where('empleado_id', $empleado->id)
                        ->where('ocupacion', 'LIKE', '%Líder%')
                        ->exists();

                    if ($esLider && $permiso === 'contratoincidencias') {
                        return Inertia::render($this->getModelName() . '/CreateUpdate', array_merge([
                            'isCreate' => true
                        ], PermissionService::getPermissions($permiso)));
                    }
                }
            }

            // Para otros roles, verificar permisos normalmente
            if (!$user->can($permiso . '-create')) {
                abort(403, 'No tienes permiso para acceder a esta sección');
            }

            return Inertia::render($this->getModelName() . '/CreateUpdate', array_merge([
                'isCreate' => true
            ], PermissionService::getPermissions($permiso)));
        } catch (\Exception $e) {
            return ResponseService::error('Error al cargar el formulario de creación', $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $permiso = strtolower($this->getModelName());
            $user = $this->getAuthenticatedUser();

            // Super Admin puede hacer todo
            if ($user->hasRole('Super Admin')) {
                $data = $this->handleRequest($request);
                $model = $this->model::create($data);
                return ResponseService::success('Registro guardado correctamente', $model);
            }

            // Cliente solo puede crear contratos e incidencias
            if ($user->hasRole('Cliente')) {
                if ($permiso === 'contrato' || $permiso === 'contratoincidencias') {
                    $data = $this->handleRequest($request);

                    // Si es un contrato, asegurarse de que el cliente_id sea el del usuario actual
                    if ($permiso === 'contrato') {
                        $data['cliente_id'] = $user->id;
                    }

                    $model = $this->model::create($data);
                    return ResponseService::success('Registro guardado correctamente', $model);
                }
                abort(403, 'No tienes permiso para realizar esta acción');
            }

            // Empleado con rol de líder puede crear incidencias de contratos
            if ($user->hasRole('Empleado')) {
                $empleado = \App\Models\Empleado::where('user_id', $user->id)->first();
                if ($empleado) {
                    $esLider = \App\Models\EmpleadoEquipoTrabajo::where('empleado_id', $empleado->id)
                        ->where('ocupacion', 'LIKE', '%Líder%')
                        ->exists();

                    if ($esLider && $permiso === 'contratoincidencias') {
                        $data = $this->handleRequest($request);
                        $model = $this->model::create($data);
                        return ResponseService::success('Registro guardado correctamente', $model);
                    }
                }
                abort(403, 'No tienes permiso para realizar esta acción');
            }

            // Para otros roles, verificar permisos normalmente
            if (!$user->can($permiso . '-create')) {
                abort(403, 'No tienes permiso para realizar esta acción');
            }

            $data = $this->handleRequest($request);
            $model = $this->model::create($data);
            return ResponseService::success('Registro guardado correctamente', $model);
        } catch (\Exception $e) {
            return ResponseService::error('Error al guardar el registro', $e->getMessage());
        }
    }

    public function edit($id)
    {
        try{
            $model = $this->model::find($id);
            if (!$model) {
                return ResponseService::error('Registro no encontrado', '', 404);
            }

            $permiso = strtolower($this->getModelName());
            $user = Auth::user();

            // Super Admin puede hacer todo
            if ($user->hasRole('Super Admin')) {
                return Inertia::render($this->getModelName() . '/CreateUpdate', array_merge([
                    'isCreate' => false,
                    'model' => $model,
                ], PermissionService::getPermissions($permiso)));
            }

            // Cliente solo puede editar sus propios contratos e incidencias
            if ($user->hasRole('Cliente')) {
                if ($permiso === 'contrato') {
                    // Verificar que el contrato pertenezca al cliente
                    if ($model->cliente_id != $user->id) {
                        abort(403, 'No tienes permiso para editar este contrato');
                    }

                    return Inertia::render($this->getModelName() . '/CreateUpdate', array_merge([
                        'isCreate' => false,
                        'model' => $model,
                    ], PermissionService::getPermissions($permiso)));
                }
                else if ($permiso === 'contratoincidencias') {
                    // Verificar que la incidencia pertenezca a un contrato del cliente
                    $contrato = \App\Models\Contrato::find($model->contrato_id);
                    if (!$contrato || $contrato->cliente_id != $user->id) {
                        abort(403, 'No tienes permiso para editar esta incidencia');
                    }

                    return Inertia::render($this->getModelName() . '/CreateUpdate', array_merge([
                        'isCreate' => false,
                        'model' => $model,
                    ], PermissionService::getPermissions($permiso)));
                }

                abort(403, 'No tienes permiso para acceder a esta sección');
            }

            // Empleado con rol de líder puede editar incidencias de contratos
            if ($user->hasRole('Empleado')) {
                $empleado = \App\Models\Empleado::where('user_id', $user->id)->first();
                if ($empleado) {
                    $esLider = \App\Models\EmpleadoEquipoTrabajo::where('empleado_id', $empleado->id)
                        ->where('ocupacion', 'LIKE', '%Líder%')
                        ->exists();

                    if ($esLider && $permiso === 'contratoincidencias') {
                        return Inertia::render($this->getModelName() . '/CreateUpdate', array_merge([
                            'isCreate' => false,
                            'model' => $model,
                        ], PermissionService::getPermissions($permiso)));
                    }
                }

                abort(403, 'No tienes permiso para acceder a esta sección');
            }

            // Para otros roles, verificar permisos normalmente
            if (!$user->can($permiso . '-edit')) {
                abort(403, 'No tienes permiso para acceder a esta sección');
            }

            return Inertia::render($this->getModelName() . '/CreateUpdate', array_merge([
                'isCreate' => false,
                'model' => $model,
            ], PermissionService::getPermissions($permiso)));
        } catch (\Exception $e) {
            return ResponseService::error('Error al cargar el formulario de edición', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $model = $this->model::find($id);
            if (!$model) {
                return ResponseService::error('Registro no encontrado', '', 404);
            }

            $permiso = strtolower($this->getModelName());
            $user = Auth::user();

            // Super Admin puede hacer todo
            if ($user->hasRole('Super Admin')) {
                $data = $this->handleRequest($request);
                $model->update($data);
                return ResponseService::success('Registro actualizado correctamente', $model);
            }

            // Cliente solo puede actualizar sus propios contratos e incidencias
            if ($user->hasRole('Cliente')) {
                if ($permiso === 'contrato') {
                    // Verificar que el contrato pertenezca al cliente
                    if ($model->cliente_id != $user->id) {
                        abort(403, 'No tienes permiso para actualizar este contrato');
                    }

                    $data = $this->handleRequest($request);
                    // Asegurar que no se cambie el cliente_id
                    $data['cliente_id'] = $user->id;

                    $model->update($data);
                    return ResponseService::success('Registro actualizado correctamente', $model);
                }
                else if ($permiso === 'contratoincidencias') {
                    // Verificar que la incidencia pertenezca a un contrato del cliente
                    $contrato = \App\Models\Contrato::find($model->contrato_id);
                    if (!$contrato || $contrato->cliente_id != $user->id) {
                        abort(403, 'No tienes permiso para actualizar esta incidencia');
                    }

                    $data = $this->handleRequest($request);
                    $model->update($data);
                    return ResponseService::success('Registro actualizado correctamente', $model);
                }

                abort(403, 'No tienes permiso para realizar esta acción');
            }

            // Empleado con rol de líder puede actualizar incidencias de contratos
            if ($user->hasRole('Empleado')) {
                $empleado = \App\Models\Empleado::where('user_id', $user->id)->first();
                if ($empleado) {
                    $esLider = \App\Models\EmpleadoEquipoTrabajo::where('empleado_id', $empleado->id)
                        ->where('ocupacion', 'LIKE', '%Líder%')
                        ->exists();

                    if ($esLider && $permiso === 'contratoincidencias') {
                        $data = $this->handleRequest($request);
                        $model->update($data);
                        return ResponseService::success('Registro actualizado correctamente', $model);
                    }
                }

                abort(403, 'No tienes permiso para realizar esta acción');
            }

            // Para otros roles, verificar permisos normalmente
            if (!$user->can($permiso . '-edit')) {
                abort(403, 'No tienes permiso para realizar esta acción');
            }

            $data = $this->handleRequest($request);
            $model->update($data);
            return ResponseService::success('Registro actualizado correctamente', $model);
        } catch (\Exception $e) {
            return ResponseService::error('Error al actualizar el registro', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $model = $this->model::find($id);
            if (!$model) {
                return ResponseService::error('Registro no encontrado', '', 404);
            }

            $permiso = strtolower($this->getModelName());
            $user = Auth::user();

            // Super Admin puede hacer todo
            if ($user->hasRole('Super Admin')) {
                $model->delete();
                return ResponseService::success('Registro eliminado correctamente');
            }

            // Cliente solo puede eliminar sus propios contratos e incidencias
            if ($user->hasRole('Cliente')) {
                if ($permiso === 'contrato') {
                    // Verificar que el contrato pertenezca al cliente
                    if ($model->cliente_id != $user->id) {
                        abort(403, 'No tienes permiso para eliminar este contrato');
                    }

                    $model->delete();
                    return ResponseService::success('Registro eliminado correctamente');
                }
                else if ($permiso === 'contratoincidencias') {
                    // Verificar que la incidencia pertenezca a un contrato del cliente
                    $contrato = \App\Models\Contrato::find($model->contrato_id);
                    if (!$contrato || $contrato->cliente_id != $user->id) {
                        abort(403, 'No tienes permiso para eliminar esta incidencia');
                    }

                    $model->delete();
                    return ResponseService::success('Registro eliminado correctamente');
                }

                abort(403, 'No tienes permiso para realizar esta acción');
            }

            // Empleado con rol de líder puede eliminar incidencias de contratos
            if ($user->hasRole('Empleado')) {
                $empleado = \App\Models\Empleado::where('user_id', $user->id)->first();
                if ($empleado) {
                    $esLider = \App\Models\EmpleadoEquipoTrabajo::where('empleado_id', $empleado->id)
                        ->where('ocupacion', 'LIKE', '%Líder%')
                        ->exists();

                    if ($esLider && $permiso === 'contratoincidencias') {
                        $model->delete();
                        return ResponseService::success('Registro eliminado correctamente');
                    }
                }

                abort(403, 'No tienes permiso para realizar esta acción');
            }

            // Para otros roles, verificar permisos normalmente
            if (!$user->can($permiso . '-delete')) {
                abort(403, 'No tienes permiso para realizar esta acción');
            }

            $model->delete();
            return ResponseService::success('Registro eliminado correctamente');
        } catch (\Exception $e) {
            return ResponseService::error('Error al eliminar el registro', $e->getMessage());
        }
    }

    protected function handleRequest(Request $request)
    {
        // Método que puede ser sobrescrito para manejar lógica específica
        return $request->all();
    }


    public function query(Request $request)
    {
        try {
            $queryStr = $request->get('query');
            $perPage = $request->get('perPage', 5);
            $page = $request->get('page', 1);
            $attributes = $request->get('attributes', ['id']); // Atributos por defecto
            $dateStart = $request->get('dateStart', '');
            $dateEnd = $request->get('dateEnd', '');
            $is_query_table = $request->get('is_query_table', false);

            // 2. Verificar que existe el modelo
            if (!isset($this->model)) {
                throw new \Exception('Modelo no definido en el controlador');
            }

            // 3. Obtener y validar atributos del modelo de manera más eficiente
            $modelAttributes = array_merge(
                $this->model->getFillable(),
                ['id', 'created_at', 'updated_at']
            );

            // 4. Validar atributos de búsqueda
            $invalidAttributes = array_diff($attributes, $modelAttributes);
            if (!empty($invalidAttributes)) {
                return ResponseService::error(
                    'Atributo(s) de búsqueda no encontrado(s): ' . implode(', ', $invalidAttributes),
                    '',
                    400
                );
            }

            $query = $this->model::query();
            foreach ($attributes as $index => $attribute) {
                $method = $index === 0 ? 'where' : 'orWhere';

                if (in_array($attribute, ['created_at', 'updated_at'])) {
                    if ($dateStart && $dateEnd) {
                        $query->whereBetween($attribute, [
                            "$dateStart 00:00:00",
                            "$dateEnd 23:59:59"
                        ]);
                    }
                } elseif ($queryStr) {
                    $query->$method($attribute, 'LIKE', "%$queryStr%");
                }
            }
            // 6. Ejecutar la consulta según el tipo
            $response = $is_query_table
                ? $query->orderBy('id', 'DESC')->paginate($perPage, ['*'], 'page', $page)
                : $query->orderBy('id', 'DESC')->get();

            $cantidad = $is_query_table ? $response->total() : $response->count();
            // 7. Retornar respuesta
            return ResponseService::success(
                "$cantidad datos encontrados con $queryStr",
                $response
            );
        } catch (\Exception $e) {
            return ResponseService::error($e->getMessage(), '', $e->getCode());
        }
    }

}
