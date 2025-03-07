<?php

namespace Database\Seeders;

use App\Models\ProgramaAcademico;
use App\Models\UnidadAcademica;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProgramaAcademicoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $unidades = UnidadAcademica::all(); // 🔹 Obtener todas las unidades

        foreach ($unidades as $unidad) {
            $programas = [
                [
                    'nombre_programa' => 'Licenciatura en Sistemas Computacionales',
                    'id_unidad' => $unidad->id_unidad,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'nombre_programa' => 'Licenciatura en Economia',
                    'id_unidad' => $unidad->id_unidad,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'nombre_programa' => 'Licenciatura en Informatica',
                    'id_unidad' => $unidad->id_unidad,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ];
            ProgramaAcademico::insert($programas);
        }
    }
}
