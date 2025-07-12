<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Traits\CrudController;

class ProductoController extends Controller
{
    use CrudController;
    public Producto $model;
    public $rutaVisita = 'Producto';
    public function __construct()
    {
        $this->model = new Producto();
    }
}
