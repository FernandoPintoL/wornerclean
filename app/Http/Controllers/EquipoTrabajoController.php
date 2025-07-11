<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEquipoTrabajoRequest;
use App\Http\Requests\UpdateEquipoTrabajoRequest;
use App\Models\EquipoTrabajo;
use App\Traits\CrudController;

class EquipoTrabajoController extends Controller
{
    use CrudController;
    public EquipoTrabajo $model;
    public $rutaVisita = 'EquipoTrabajo';
    public function __construct()
    {
        $this->model = new EquipoTrabajo();
    }
}
