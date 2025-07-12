<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Traits\CrudController;

class ClienteController extends Controller
{
    use CrudController;
    public Cliente $model;
    public $rutaVisita = 'Cliente';
    public function __construct()
    {
        $this->model = new Cliente();
    }
}
