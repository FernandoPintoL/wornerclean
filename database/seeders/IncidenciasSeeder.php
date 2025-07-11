<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IncidenciasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // incidencias en el transcurso de un servicio de limpieza
        $incidencias = [
            [
                'nombre' => 'Suministros',
                'descripcion' => 'Falta de suministros de limpieza',
            ],
            [
                'nombre' => 'Equipo Dañado',
                'descripcion' => 'Equipo de limpieza dañado o inoperativo',
            ],
            [
                'nombre' => 'Retraso',
                'descripcion' => 'Retraso en la llegada del personal de limpieza',
            ],
            [
                'nombre' => 'Queja del Cliente',
                'descripcion' => 'Queja del cliente sobre la calidad del servicio',
            ],
            [
                'nombre' => 'Accidente',
                'descripcion' => 'Accidente ocurrido durante el servicio de limpieza',
            ],
            [
                'nombre' => 'Falta de Personal',
                'descripcion' => 'Falta de personal asignado para el servicio programado',
            ],
        ];
        // Insertar las incidencias en la base de datos
        \App\Models\Incidencias::insert($incidencias);
    }
}
