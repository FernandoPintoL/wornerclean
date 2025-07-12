<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Traits\CrudController;

class ServicioController extends Controller
{
    use CrudController;
    public Servicio $model;
    public $rutaVisita = 'Servicio';
    public function __construct()
    {
        $this->model = new Servicio();
    }
}
