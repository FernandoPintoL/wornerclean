<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipoTrabajoServicio extends Model
{
    /** @use HasFactory<\Database\Factories\EquipoTrabajoServicioFactory> */
    use HasFactory;
    protected $table = 'equipo_trabajo_servicios';
    protected $primaryKey = 'id';
    protected $fillable = [
        'equipo_trabajo_id',
        'servicio_id',
        'estado'
    ];
}
