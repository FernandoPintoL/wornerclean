<?php

namespace App\Http\Controllers;

use App\Models\EquipoTrabajoServicio;
use App\Traits\CrudController;

class EquipoServicioController extends Controller
{
    use CrudController;
    public EquipoTrabajoServicio $model;
    public $rutaVisita = 'EquipoServicio';
    public function __construct()
    {
        $this->model = new EquipoTrabajoServicio();
    }
}
