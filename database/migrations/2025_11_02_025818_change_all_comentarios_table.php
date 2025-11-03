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
        Schema::table('comentario_avance', function (Blueprint $table) {
            $table->renameColumn('contenido_original','rango_seleccionado');

            
        });

        // Paso 2: cambiar el tipo de la columna
        Schema::table('comentario_avance', function (Blueprint $table) {
            $table->json('rango_seleccionado')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comentario_avance', function (Blueprint $table) {
            $table->renameColumn('rango_seleccionado','contenido_original');

            $table->text('contenido_original')->change();
        });

    }
};
