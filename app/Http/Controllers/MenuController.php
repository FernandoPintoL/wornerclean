<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Services\ResponseService;
use App\Traits\CrudController;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MenuController extends Controller
{
    use CrudController;
    public string $rutaVisita = 'Menu';
    public Menu $model;

    public function __construct()
    {
        $this->model = new Menu();
    }

    /**
     * Custom request handling for menu items
     */
    protected function handleRequest(Request $request)
    {
        // Validate the request data
        $request->validate([
            'title' => 'required|string|max:255',
            'href' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'isMain' => 'required|boolean',
            'isSubmenu' => 'required|boolean',
            'parent_id' => [
                'nullable',
                'integer',
                Rule::exists('menus', 'id')->where(function ($query) {
                    $query->where('isMain', true);
                }),
            ],
        ]);

        $data = $request->all();

        // Apply business logic
        if ($data['isMain']) {
            // Main menus can't be submenus and don't have parents
            $data['isSubmenu'] = false;
            $data['parent_id'] = null;
        } elseif ($data['isSubmenu']) {
            // Submenus must have a parent
            if (empty($data['parent_id'])) {
                throw new \Exception('Un submenú debe tener un menú padre');
            }
        }

        return $data;
    }

    /**
     * Override the query method to filter menus based on user role
     */
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

            // Verificar que existe el modelo
            if (!isset($this->model)) {
                throw new \Exception('Modelo no definido en el controlador');
            }

            // Obtener y validar atributos del modelo
            $modelAttributes = array_merge(
                $this->model->getFillable(),
                ['id', 'created_at', 'updated_at']
            );

            // Validar atributos de búsqueda
            $invalidAttributes = array_diff($attributes, $modelAttributes);
            if (!empty($invalidAttributes)) {
                return ResponseService::error(
                    'Atributo(s) de búsqueda no encontrado(s): ' . implode(', ', $invalidAttributes),
                    '',
                    400
                );
            }

            $query = $this->model::query();

            // Filtrar menús según el rol del usuario
            $user = \Illuminate\Support\Facades\Auth::user();

            // Verificar si el usuario está autenticado
            /*if ($user) {
                if ($user->hasRole('Super Admin')) {
                    // Super Admin ve todos los menús
                } elseif ($user->hasRole('Cliente')) {
                    // Cliente solo ve menús de contratos e incidencias
                    $query->where(function($q) {
                        $q->where('href', 'LIKE', '%contrato%')
                          ->orWhere('href', 'LIKE', '%incidencia%');
                    });
                } elseif ($user->hasRole('Empleado')) {
                    // Verificar si es líder de equipo
                    $empleado = \App\Models\Empleado::where('user_id', $user->id)->first();
                    if ($empleado) {
                        $esLider = \App\Models\EmpleadoEquipoTrabajo::where('empleado_id', $empleado->id)
                            ->where('ocupacion', 'LIKE', '%Líder%')
                            ->exists();

                        if ($esLider) {
                            // Líderes de equipo ven menús de incidencias
                            $query->where('href', 'LIKE', '%incidencia%');
                        } else {
                            // Otros empleados ven menús según sus permisos
                            // Aquí se podría implementar lógica adicional si es necesario
                        }
                    }
                }
            } else {
                // Si el usuario no está autenticado, mostrar solo menús públicos o ninguno
                $query->where('href', 'LIKE', '%public%');
            }*/

            // Aplicar filtros de búsqueda
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

            // Ejecutar la consulta según el tipo
            $response = $is_query_table
                ? $query->orderBy('id', 'DESC')->paginate($perPage, ['*'], 'page', $page)
                : $query->orderBy('id', 'DESC')->get();

//            return $response;

            $cantidad = $is_query_table ? $response->total() : $response->count();

            return ResponseService::success(
                "$cantidad datos encontrados con $queryStr",
                $response
            );
        } catch (\Exception $e) {
            return ResponseService::error($e->getMessage(), '', $e->getCode());
        }
    }

    /**
     * Get all main menus for dropdown selection
     */
    public function getMainMenus()
    {
        try {
            $mainMenus = $this->model::where('isMain', true)->get(['id', 'title']);
            return ResponseService::success('Menús principales obtenidos correctamente', $mainMenus);
        } catch (\Exception $e) {
            return ResponseService::error('Error al obtener los menús principales', $e->getMessage());
        }
    }
}
