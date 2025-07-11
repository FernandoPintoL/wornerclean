<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::create(['name' => 'Super Admin']);
        $administrador = Role::create(['name' => 'Administrador']);
        $cliente = Role::create(['name' => 'Cliente']);
        $empleado = Role::create(['name' => 'Empleado']);

        // Asignar permisos al rol de Super Admin
        $admin->givePermissionTo(Permission::all());

        $user = User::find(1);
        $user->assignRole([$admin]);
        $user->givePermissionTo(Permission::all());

        $cliente = User::find(2);

        $empleado = User::find(3);
    }
}
