<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmpleadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //empleados en español
        $empleados = [
            [
                'ci' => '12345678',
                'nombre' => 'Empleado Uno',
                'telefono' => '1234567890',
                'puesto' => 'Gerente',
                'estado' => 'Activo',
            ],
            [
                'ci' => '87654321',
                'nombre' => 'Empleado Dos',
                'telefono' => '0987654321',
                'puesto' => 'Vendedor',
                'estado' => 'Activo',
            ],
            [
                'ci' => '11223344',
                'nombre' => 'Empleado Tres',
                'telefono' => '1122334455',
                'puesto' => 'Cajero',
                'estado' => 'Inactivo',
            ],
            [
                'ci' => '44332211',
                'nombre' => 'Empleado Cuatro',
                'telefono' => '5544332211',
                'puesto' => 'Asistente Administrativo',
                'estado' => 'Activo',
            ],
            [
                'ci' => '55667788',
                'nombre' => 'Empleado Cinco',
                'telefono' => '6677889900',
                'puesto' => 'Supervisor de Ventas',
                'estado' => 'Activo',
            ],
            [
                'ci' => '99887766',
                'nombre' => 'Empleado Seis',
                'telefono' => '9988776655',
                'puesto' => 'Personal de Limpieza',
                'estado' => 'Activo',
            ],
            [
                'ci' => '55443322',
                'nombre' => 'Empleado Siete',
                'telefono' => '5544332211',
                'puesto' => 'Personal de Limpieza',
                'estado' => 'Inactivo',
            ],
            [
                'ci' => '66778899',
                'nombre' => 'Empleado Ocho',
                'telefono' => '6677889900',
                'puesto' => 'Personal de Limpieza',
                'estado' => 'Activo',
            ],
            [
                'ci' => '11223355',
                'nombre' => 'Empleado Nueve',
                'telefono' => '1122334455',
                'puesto' => 'Personal de Limpieza',
                'estado' => 'Inactivo',
            ],
            [
                'ci' => '99887755',
                'nombre' => 'Empleado Diez',
                'telefono' => '9988776655',
                'puesto' => 'Personal de Limpieza',
                'estado' => 'Activo',
            ],
        ];
        // Insertar los empleados en la base de datos
        \App\Models\Empleado::insert($empleados);
    }
}
