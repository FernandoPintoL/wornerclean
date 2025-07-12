<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Traits\CrudController;

class EmpleadoController extends Controller
{
    use CrudController;
    public Empleado $model;
    public $rutaVisita = 'Empleado';
    public function __construct()
    {
        $this->model = new Empleado();
    }
}
