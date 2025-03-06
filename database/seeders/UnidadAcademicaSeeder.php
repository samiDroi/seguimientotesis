<?php

namespace Database\Seeders;

use App\Models\UnidadAcademica;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnidadAcademicaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $unidades = [
            ['nombre_unidad' => 'Unidad Academica de Economia'],
            
        ];

        foreach ($unidades as $unidad) {
            UnidadAcademica::create($unidad);
        }
    }
}
