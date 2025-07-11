<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    /** @use HasFactory<\Database\Factories\EmpleadoFactory> */
    use HasFactory;
    protected $table = 'empleados';
    protected $primaryKey = 'id';
    protected $fillable = [
        'ci',
        'nombre',
        'email',
        'telefono',
        'puesto',
        'estado',
        'user_id'
    ];
}
