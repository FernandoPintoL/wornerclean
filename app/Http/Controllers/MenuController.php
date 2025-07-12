<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Traits\CrudController;

class MenuController extends Controller
{
    use CrudController;
    public string $rutaVisita = 'Menu';
    public Menu $model;
    public function __construct()
    {
        $this->model = new Menu();
    }
}
