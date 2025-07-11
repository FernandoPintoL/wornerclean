<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // clientes en español
        $clientes = [
            [
                'ci' => '12345678',
                'nombre' => 'Cliente Uno',
                'telefono' => '1234567890',
                'direccion' => 'Calle Falsa 123, Ciudad, País',
                'tipo_cliente' => 'Regular'
            ],
            [
                'ci' => '87654321',
                'nombre' => 'Cliente Dos',
                'telefono' => '0987654321',
                'direccion' => 'Avenida Siempre Viva 456, Ciudad, País',
                'tipo_cliente' => 'Premium'
            ],
            [
                'ci' => '11223344',
                'nombre' => 'Cliente Tres',
                'telefono' => '1122334455',
                'direccion' => 'Boulevard de los Sueños Rotos 789, Ciudad, País',
                'tipo_cliente' => 'Regular'
            ],
            [
                'ci' => '44332211',
                'nombre' => 'Cliente Cuatro',
                'telefono' => '5544332211',
                'direccion' => 'Plaza Mayor 101, Ciudad, País',
                'tipo_cliente' => 'VIP'
            ],
            [
                'ci' => '55667788',
                'nombre' => 'Cliente Cinco',
                'telefono' => '6677889900',
                'direccion' => 'Calle del Sol 202, Ciudad, País',
                'tipo_cliente' => 'Regular'
            ],
        ];
        // Insertar los clientes en la base de datos
        \App\Models\Cliente::insert($clientes);
    }
}
