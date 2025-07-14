<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $arrays_menus = [
            // #1
            [
                'title' => 'Menu',
                'href' => 'menu.index',
                'icon' => "fa-solid fa-bars",
                'isMain' => true,
                'isSubMenu' => false
            ],
            // #2
            [
                'title' => 'Usuarios',
                'href' => 'users.index',
                'icon' => "fa-solid fa-users-rectangle",
                'isMain' => true,
                'isSubMenu' => false
            ],
            // #3
            [
                'title' => 'Servicios',
                'href' => 'servicio.index',
                'icon' => "fa-solid fa-list-check",
                'isMain' => true,
                'isSubMenu' => false
            ],
            // #4
            [
                'title' => 'Equipo de Trabajo',
                'href' => 'equipo-trabajo.index',
                'icon' => "fa-solid fa-users",
                'isMain' => true,
                'isSubMenu' => false
            ],
            // #5
            [
                'title' => 'Productos',
                'href' => 'producto.index',
                'icon' => "fa-brands fa-product-hunt",
                'isMain' => true,
                'isSubMenu' => false
            ],
            // #6
            [
                'title' => 'Incidencias',
                'href' => 'incidencias.index',
                'icon' => "fa-solid fa-person-shelter",
                'isMain' => true,
                'isSubMenu' => false
            ],
            // #7
            [
                'title' => 'Empleado',
                'href' => 'empleado.index',
                'icon' => "fa-solid fa-user",
                'isMain' => true,
                'isSubMenu' => false
            ],
            // #8
            [
                'title' => 'Cliente',
                'href' => 'cliente.index',
                'icon' => "fa-solid fa-person",
                'isMain' => true,
                'isSubMenu' => false
            ],
            // #9
            [
                'title' => 'Contratos',
                'href' => 'contrato.index',
                'icon' => "fa-solid fa-file-contract",
                'isMain' => true,
                'isSubMenu' => false
            ],
            // #10
            [
                'title' => 'Dashboard',
                'href' => 'dashboard',
                'icon' => "fa-solid fa-house",
                'isMain' => true,
                'isSubMenu' => false
            ],
            //submenus
            [
                'title' => 'Roles',
                'href' => 'roles.index',
                'icon' => "fa-solid fa-person-through-window",
                'isMain' => false,
                'isSubMenu' => true,
                'parent_id' => 2
            ],
            [
                'title' => 'Permisos',
                'href' => 'permissions.index',
                'icon' => "fa-solid fa-key",
                'isMain' => false,
                'isSubMenu' => true,
                'parent_id' => 2
            ],
        ];

        foreach ($arrays_menus as $menu) {
            Menu::create([
                'title' => $menu['title'],
                'href' =>  $menu['href'],
                'icon' => $menu['icon'],
                'isMain' => $menu['isMain'],
                'isSubmenu' => $menu['isSubMenu'],
                'parent_id' => $menu['parent_id'] ?? null,
            ]);
        }
    }
}
