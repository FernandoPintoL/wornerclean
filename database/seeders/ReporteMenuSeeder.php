<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class ReporteMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Menu::create([
            'title' => 'Reportes',
            'href' => 'reportes.index',
            'icon' => 'fas fa-chart-bar',
            'isMain' => true,
            'isSubmenu' => false,
            'parent_id' => null,
        ]);
    }
}
