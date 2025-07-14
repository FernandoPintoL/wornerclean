<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ReportePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener roles
        $superAdmin = Role::where('name', 'Super Admin')->first();
        $admin = Role::where('name', 'Admin')->first();
        $cliente = Role::where('name', 'Cliente')->first();
        $empleado = Role::where('name', 'Empleado')->first();

        // Obtener permisos de reportes
        $reportePermissions = Permission::where('name', 'like', 'reporte-%')->get();

        // Asignar todos los permisos de reportes al Super Admin y Admin
        if ($superAdmin) {
            $superAdmin->givePermissionTo($reportePermissions);
        }

        if ($admin) {
            $admin->givePermissionTo($reportePermissions);
        }

        // Asignar permiso de ver reportes a Cliente y Empleado
        $reporteView = Permission::where('name', 'reporte-view')->first();
        $reporteList = Permission::where('name', 'reporte-list')->first();
        $reporteIndex = Permission::where('name', 'reporte-index')->first();
        $reporteShow = Permission::where('name', 'reporte-show')->first();

        if ($cliente) {
            if ($reporteView) $cliente->givePermissionTo($reporteView);
            if ($reporteList) $cliente->givePermissionTo($reporteList);
            if ($reporteIndex) $cliente->givePermissionTo($reporteIndex);
            if ($reporteShow) $cliente->givePermissionTo($reporteShow);
        }

        if ($empleado) {
            if ($reporteView) $empleado->givePermissionTo($reporteView);
            if ($reporteList) $empleado->givePermissionTo($reporteList);
            if ($reporteIndex) $empleado->givePermissionTo($reporteIndex);
            if ($reporteShow) $empleado->givePermissionTo($reporteShow);
        }
    }
}
