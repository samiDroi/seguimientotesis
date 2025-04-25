<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\UnidadAcademica;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // // ]);
        // $this->call(TiposUsuariosSeeder::class); 
        // $this->call(UnidadAcademicaSeeder::class);
        // $this->call(ProgramaAcademicoSeeder::class);
        // $this->call(PermisosSeeder::class);
        $this->call(RolesSeeder::class);
    }
}
