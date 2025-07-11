<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServicioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // tipo de servicios de limpieza en español
        $servicios = [
            [
                'nombre' => 'Limpieza de Oficinas',
                'descripcion' => 'Servicio de limpieza completo para oficinas y espacios de trabajo.',
                'precio' => 100.00,
                'frecuencia' => 'Diario', // en fracciones de tiempo
                'estado' => 'Activo', // Activo o Inactivo
            ],
            [
                'nombre' => 'Limpieza de Hogares',
                'descripcion' => 'Servicio de limpieza a fondo para hogares y residencias.',
                'precio' => 80.00,
                'frecuencia' => 'Semanal', // en fracciones de tiempo
                'estado' => 'Activo', // Activo o Inactivo
            ],
            [
                'nombre' => 'Limpieza de Escuelas',
                'descripcion' => 'Servicio de limpieza especializado para instituciones educativas.',
                'precio' => 120.00,
                'frecuencia' => 'Mensual', // en fracciones de tiempo
                'estado' => 'Activo', // Activo o Inactivo
            ],
            [
                'nombre' => 'Limpieza de Hospitales',
                'descripcion' => 'Servicio de limpieza rigurosa para hospitales y clínicas.',
                'precio' => 200.00,
                'frecuencia' => 'Diario', // en fracciones de tiempo
                'estado' => 'Activo', // Activo o Inactivo
            ],
            [
                'nombre' => 'Limpieza de Eventos',
                'descripcion' => 'Servicio de limpieza antes y después de eventos y celebraciones.',
                'precio' => 150.00,
                'frecuencia' => 'Por Evento', // en fracciones de tiempo
                'estado' => 'Activo', // Activo o Inactivo
            ],
            [
                'nombre' => 'Limpieza de Áreas Exteriores',
                'descripcion' => 'Servicio de limpieza de jardines, patios y áreas exteriores.',
                'precio' => 90.00,
                'frecuencia' => 'Mensual', // en fracciones de tiempo
                'estado' => 'Activo', // Activo o Inactivo
            ],
        ];
        // Insertar los servicios en la base de datos
        \App\Models\Servicio::insert($servicios);
    }
}
