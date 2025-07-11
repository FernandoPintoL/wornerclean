<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContratoRequest;
use App\Http\Requests\UpdateContratoRequest;
use App\Models\Contrato;
use App\Traits\CrudController;

class ContratoController extends Controller
{
    use CrudController;
    public Contrato $model;
    public $rutaVisita = 'Contrato';
    public function __construct()
    {
        $this->model = new Contrato();
    }
}
