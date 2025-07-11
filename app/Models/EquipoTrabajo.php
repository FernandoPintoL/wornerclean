<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipoTrabajo extends Model
{
    /** @use HasFactory<\Database\Factories\EquipoTrabajoFactory> */
    use HasFactory;
    protected $table = 'equipo_trabajos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nombre',
        'descripcion',
        'estado',
        'user_id'
    ];
}
