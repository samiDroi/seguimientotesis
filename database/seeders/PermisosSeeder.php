<?php

namespace Database\Seeders;

use App\Models\Permiso;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermisosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permisos = ['tesis.create','tesis.edit','tesis.read','tesis.comment'];
        $descripciones = ['crear la estructura de la tesis','editar la estructura de la tesis','leer los avances de tesis','realizar comentarios a los avances de tesis'];
        // foreach ($permisos as $permiso) {
        //     Permiso::create([
        //         'clave' => $permiso,
        //         'descripcion' => $descripciones
        //     ]);
        // }
        foreach ($permisos as $i => $permiso) {
            Permiso::create([
                'clave' => $permiso,
                'descripcion' => $descripciones[$i]
            ]);
        }
    }

}