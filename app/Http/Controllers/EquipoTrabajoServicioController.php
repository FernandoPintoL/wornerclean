<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEquipoTrabajoServicioRequest;
use App\Http\Requests\UpdateEquipoTrabajoServicioRequest;
use App\Models\EquipoTrabajoServicio;
use App\Traits\CrudController;

class EquipoTrabajoServicioController extends Controller
{
    use CrudController;
    public EquipoTrabajoServicio $model;
    public $rutaVisita = 'EquipoTrabajoServicio';
    public function __construct()
    {
        $this->model = new EquipoTrabajoServicio();
    }
}
