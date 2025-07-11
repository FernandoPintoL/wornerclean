<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    /** @use HasFactory<\Database\Factories\ServicioFactory> */
    use HasFactory;
    protected $table = 'servicios';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'frecuencia',
        'estado'
    ];
}
