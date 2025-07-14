<?php

namespace App\Http\Controllers;

use App\Models\ContratoIncidencias;
use App\Traits\CrudController;
use Illuminate\Http\Request;
use App\Services\ResponseService;

class ContratoIncidenciasController extends Controller
{
    use CrudController;
    public ContratoIncidencias $model;
    public $rutaVisita = 'ContratoIncidencias';
    public function __construct()
    {
        $this->model = new ContratoIncidencias();
    }

    /**
     * Get all incidents for a specific contract
     */
    public function getIncidenciasByContrato($contratoId)
    {
        try {
            $incidencias = $this->model::with('incidencia')
                ->where('contrato_id', $contratoId)
                ->orderBy('created_at', 'desc')
                ->get();

            return ResponseService::success('Incidencias obtenidas correctamente', $incidencias);
        } catch (\Exception $e) {
            return ResponseService::error('Error al obtener las incidencias', $e->getMessage());
        }
    }
}
