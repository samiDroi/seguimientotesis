<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TiposUsuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tipo_usuario')->insert([
            ["nombre_tipo"=>"alumno"],
            ["nombre_tipo"=>"docente"],
            ["nombre_tipo"=>"coordinador"]
        ]);
    }
}
