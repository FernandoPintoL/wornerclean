<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incidencias extends Model
{
    /** @use HasFactory<\Database\Factories\IncidenciasFactory> */
    use HasFactory;
    protected $table = 'incidencias';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nombre',
        'descripcion'
    ];
}
