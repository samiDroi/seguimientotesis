<?php

namespace Database\Seeders;

use App\Models\Rol;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ['administrador', 'editor', 'revisor', 'lector'];
        $descripciones = ['Puede editar y generar la estructura de la tesis, asi como leer los avances de la tesis y hacer comentarios','Puede realizar ediciones a la estructura de la tesis','Puede leer y hacer comentarios de los avances de la tesis','Solamente puede leer los avances de la tesis'];
        foreach ($roles as $i => $rol) {
            Rol::create([
                'nombre_rol' => $rol,
                'descripcion' => $descripciones[$i]
        ]);
        }
    }
}
