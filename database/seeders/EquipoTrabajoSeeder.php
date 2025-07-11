<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EquipoTrabajoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Equipos de trabajo para limpieza y mantenimiento
        $equipos_trabajos = [
            [
                'nombre' => 'Equipo de Limpieza',
                'descripcion' => 'Equipo encargado de la limpieza y mantenimiento de las instalaciones.',
                'estado' => 'Activo',
            ],
            [
                'nombre' => 'Equipo de Mantenimiento',
                'descripcion' => 'Equipo encargado del mantenimiento preventivo y correctivo de las instalaciones.',
                'estado' => 'Activo',
            ],
        ];
        foreach ($equipos_trabajos as $equipo) {
            \App\Models\EquipoTrabajo::create($equipo);
        }
    }
}
