<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('usuarios_programa_academico', function (Blueprint $table) { // O puedes usar $table->bigIncrements('id') si prefieres

            // Llave foránea a la tabla 'usuarios'
            $table->foreignId('id_user')->constrained('usuarios', 'id_user')->onDelete('cascade');

            // Llave foránea a la tabla 'programa_academico'
            $table->foreignId('id_programa')->constrained('programa_academico', 'id_programa')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios_programa_academico');
    }
};
