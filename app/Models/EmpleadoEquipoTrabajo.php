<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpleadoEquipoTrabajo extends Model
{
    /** @use HasFactory<\Database\Factories\EmpleadoEquipoTrabajoFactory> */
    use HasFactory;
    protected $table = 'empleado_equipo_trabajo';
    protected $primaryKey = 'id';
    protected $fillable = [
        'empleado_id',
        'equipo_trabajo_id',
        'estado',
        'ocupacion'
    ];
}
