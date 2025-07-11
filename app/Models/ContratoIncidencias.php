<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContratoIncidencias extends Model
{
    /** @use HasFactory<\Database\Factories\ContratoIncidenciasFactory> */
    use HasFactory;
    protected $table = 'contrato_incidencias';
    protected $primaryKey = 'id';
    protected $fillable = [
        'contrato_id',
        'incidencia_id',
        'estado',
        'fecha_solucion'
    ];
}
