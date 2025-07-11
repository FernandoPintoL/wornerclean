<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EquipoTrabajoServicioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // equipos de trabajo y servicios asociados
        $equipos_servicios = [
            [
                'equipo_trabajo_id' => 1, // ID del equipo de limpieza
                'servicio_id' => 1, // ID del servicio de limpieza de oficinas
                'estado' => 'Activo', // Activo o Inactivo
            ],
            [
                'equipo_trabajo_id' => 1, // ID del equipo de limpieza
                'servicio_id' => 2, // ID del servicio de limpieza de hogares
                'estado' => 'Activo', // Activo o Inactivo
            ],
            [
                'equipo_trabajo_id' => 2, // ID del equipo de mantenimiento
                'servicio_id' => 3, // ID del servicio de limpieza de escuelas
                'estado' => 'Activo', // Activo o Inactivo
            ],
            [
                'equipo_trabajo_id' => 2, // ID del equipo de mantenimiento
                'servicio_id' => 4, // ID del servicio de limpieza de hospitales
                'estado' => 'Activo', // Activo o Inactivo
            ],
        ];
        // Insertar los equipos de trabajo y servicios asociados en la base de datos
        foreach ($equipos_servicios as $equipo_servicio) {
            \App\Models\EquipoTrabajoServicio::create($equipo_servicio);
        }
    }
}
