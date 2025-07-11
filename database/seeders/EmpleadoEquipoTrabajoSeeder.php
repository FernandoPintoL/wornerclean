<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmpleadoEquipoTrabajoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // empleados asociados a equipos de trabajo
        $empleados_equipos = [
            [
                'empleado_id' => 1, // ID del empleado Juan Pérez
                'equipo_trabajo_id' => 1, // ID del equipo de limpieza
                'estado' => 'Activo', // Estado del empleado en el equipo
                'ocupacion' => 'Líder de Limpieza', // Rol del empleado en el equipo
            ],
            [
                'empleado_id' => 2, // ID del empleado María López
                'equipo_trabajo_id' => 1, // ID del equipo de limpieza
                'estado' => 'Activo', // Estado del empleado en el equipo
                'ocupacion' => 'Técnico de Limpieza', // Rol del empleado en el equipo
            ],
            [
                'empleado_id' => 3, // ID del empleado Carlos García
                'equipo_trabajo_id' => 2, // ID del equipo de mantenimiento
                'estado' => 'Activo', // Estado del empleado en el equipo
                'ocupacion' => 'Líder de Limpieza', // Rol del empleado en el equipo
            ],
            [
                'empleado_id' => 4, // ID del empleado Ana Torres
                'equipo_trabajo_id' => 2, // ID del equipo de mantenimiento
                'estado' => 'Activo', // Estado del empleado en el equipo
                'ocupacion' => 'Técnico en Mantenimiento', // Rol del empleado en el equipo
            ],
            [
                'empleado_id' => 4, // ID del empleado Ana Torres
                'equipo_trabajo_id' => 2, // ID del equipo de mantenimiento
                'estado' => 'Activo', // Estado del empleado en el equipo
                'ocupacion' => 'Técnico en Reparaciones', // Rol del empleado en el equipo
            ],
        ];
        // Insertar los empleados asociados a equipos de trabajo en la base de datos
        foreach ($empleados_equipos as $empleado_equipo) {
            \App\Models\EmpleadoEquipoTrabajo::create($empleado_equipo);
        }
    }
}
