<?php

namespace App\Http\Controllers;

use App\Models\Incidencias;
use App\Traits\CrudController;

class IncidenciasController extends Controller
{
    use CrudController;
    public Incidencias $model;
    public $rutaVisita = 'Incidencias';
    public function __construct()
    {
        $this->model = new Incidencias();
    }
}
