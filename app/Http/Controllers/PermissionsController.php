<?php

namespace App\Http\Controllers;

use App\Traits\CrudController;
use Spatie\Permission\Models\Permission;

class PermissionsController extends Controller
{
    use CrudController;
    public Permission $model;
    public $rutaVisita = 'Permissions';
    public function __construct()
    {
        $this->model = new Permission();
    }
}
