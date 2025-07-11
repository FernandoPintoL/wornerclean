<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductoServicio extends Model
{
    /** @use HasFactory<\Database\Factories\ProductoServicioFactory> */
    use HasFactory;
    protected $table = 'productos_servicios';
    protected $primaryKey = 'id';
    protected $fillable = [
        'cantidad' // Solo para productos
    ];
}
