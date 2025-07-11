<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductoServicioRequest;
use App\Http\Requests\UpdateProductoServicioRequest;
use App\Models\ProductoServicio;
use App\Traits\CrudController;

class ProductoServicioController extends Controller
{
    use CrudController;
    public ProductoServicio $model;
    public $rutaVisita = 'ProductoServicio';
    public function __construct()
    {
        $this->model = new ProductoServicio();
    }
}
