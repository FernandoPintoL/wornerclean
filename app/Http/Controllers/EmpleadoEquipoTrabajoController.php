<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmpleadoEquipoTrabajoRequest;
use App\Http\Requests\UpdateEmpleadoEquipoTrabajoRequest;
use App\Models\EmpleadoEquipoTrabajo;
use App\Traits\CrudController;

class EmpleadoEquipoTrabajoController extends Controller
{
    use CrudController;
    public EmpleadoEquipoTrabajo $model;
    public $rutaVisita = 'EmpleadoEquipoTrabajo';
    public function __construct()
    {
        $this->model = new EmpleadoEquipoTrabajo();
    }
}
