<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductoServicioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // productos asociados en servicios de limpieza
        $productos_servicios = [
            [
                'producto_id' => 1, // ID del producto de detergente
                'servicio_id' => 1, // ID del servicio de limpieza de oficinas
                'cantidad' => 5, // Cantidad del producto utilizado
            ],
            [
                'producto_id' => 2, // ID del producto de desinfectante
                'servicio_id' => 2, // ID del servicio de limpieza de hogares
                'cantidad' => 3, // Cantidad del producto utilizado
            ],
            [
                'producto_id' => 3, // ID del producto de aspiradora
                'servicio_id' => 3, // ID del servicio de limpieza de escuelas
                'cantidad' => 2, // Cantidad del producto utilizado
            ],
            [
                'producto_id' => 4, // ID del producto de mopas
                'servicio_id' => 4, // ID del servicio de limpieza de hospitales
                'cantidad' => 4, // Cantidad del producto utilizado
            ],
        ];
        // Insertar los productos asociados en servicios de limpieza en la base de datos
        foreach ($productos_servicios as $producto_servicio) {
            \App\Models\ProductoServicio::create($producto_servicio);
        }

    }
}
