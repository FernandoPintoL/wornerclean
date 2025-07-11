<?php

namespace App\Http\Controllers;

use App\Models\ContratoIncidencias;
use App\Http\Requests\StoreContratoIncidenciasRequest;
use App\Http\Requests\UpdateContratoIncidenciasRequest;
use App\Traits\CrudController;

class ContratoIncidenciasController extends Controller
{
    use CrudController;
    public ContratoIncidencias $model;
    public $rutaVisita = 'ContratoIncidencias';
    public function __construct()
    {
        $this->model = new ContratoIncidencias();
    }
}
