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
            [
                'title' => 'Dashboard',
                'href' => 'dashboard',
                'icon' => "fa-solid fa-house"
            ],
            [
                'title' => 'Contratos',
                'href' => 'contrato.index',
                'icon' => "fa-solid fa-file-contract"
            ],
            [
                'title' => 'Cliente',
                'href' => 'cliente.index',
                'icon' => "fa-solid fa-person"
            ],
            [
                'title' => 'Empleado',
                'href' => 'empleado.index',
                'icon' => "fa-solid fa-user"
            ],
            [
                'title' => 'Incidencias',
                'href' => 'incidencias.index',
                'icon' => "fa-solid fa-person-shelter"
            ],
            [
                'title' => 'Productos',
                'href' => 'producto.index',
                'icon' => "fa-brands fa-product-hunt"
            ],
            [
                'title' => 'Equipo de Trabajo',
                'href' => 'equipo-trabajo.index',
                'icon' => "fa-solid fa-users"
            ],
            [
                'title' => 'Servicios',
                'href' => 'servicio.index',
                'icon' => "fa-solid fa-list-check"
            ],
            [
                'title' => 'Usuarios',
                'href' => 'users.index',
                'icon' => "fa-solid fa-users-rectangle"
            ],
            [
                'title' => 'Menu',
                'href' => 'menu.index',
                'icon' => "fa-solid fa-bars"
            ],
            [
                'title' => 'Role',
                'href' => 'roles.index',
                'icon' => "fa-solid fa-person-through-window"
            ],
            [
                'title' => 'Permisos',
                'href' => 'permissions.index',
                'icon' => "fa-solid fa-key"
            ],

        ];


        foreach ($arrays_menus as $menu) {
            Menu::create([
                'title' => $menu['title'],
                'href' =>  $menu['href'],
                'icon' => $menu['icon'],
                'isMain' => 1,
            ]);
        }
    }
}
