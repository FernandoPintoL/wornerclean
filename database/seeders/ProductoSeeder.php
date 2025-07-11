<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // productos de limpieza en español
        $productos = [
            [
                'nombre' => 'Detergente Líquido',
                'descripcion' => 'Detergente líquido concentrado para lavar ropa.',
                'precio' => 25.00,
                'stock' => 100,
            ],
            [
                'nombre' => 'Limpiador Multiusos',
                'descripcion' => 'Limpiador multiusos para superficies duras.',
                'precio' => 15.00,
                'stock' => 200,
            ],
            [
                'nombre' => 'Desinfectante en Spray',
                'descripcion' => 'Desinfectante en spray para eliminar gérmenes y bacterias.',
                'precio' => 20.00,
                'stock' => 150,
            ],
            [
                'nombre' => 'Jabón Líquido para Manos',
                'descripcion' => 'Jabón líquido antibacterial para manos.',
                'precio' => 10.00,
                'stock' => 300,
            ],
            [
                'nombre' => 'Limpiador de Vidrios',
                'descripcion' => 'Limpiador de vidrios y espejos sin rayas.',
                'precio' => 12.00,
                'stock' => 180,
            ],
            [
                'nombre' => 'Ambientador en Aerosol',
                'descripcion' => 'Ambientador en aerosol con fragancia fresca.',
                'precio' => 8.00,
                'stock' => 250,
            ],
            [
                'nombre' => 'Limpiador de Baños',
                'descripcion' => 'Limpiador especializado para inodoros y lavabos.',
                'precio' => 18.00,
                'stock' => 120,
            ],
            [
                'nombre' => 'Esponjas de Limpieza',
                'descripcion' => 'Esponjas de limpieza duraderas y absorbentes.',
                'precio' => 5.00,
                'stock' => 500,
            ],
            [
                'nombre' => 'Guantes de Limpieza',
                'descripcion' => 'Guantes de limpieza desechables para protección.',
                'precio' => 3.00,
                'stock' => 600,
            ],
        ];
        // Insertar los productos en la base de datos
        \App\Models\Producto::insert($productos);
    }
}
