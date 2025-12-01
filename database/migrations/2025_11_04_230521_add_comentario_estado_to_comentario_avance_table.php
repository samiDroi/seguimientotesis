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
            // $table->string('comentario_estado')->default('PENDIENTE')->after('comentario_descripcion');
            $table->enum('comentario_estado',['CORREGIDO','PENDIENTE','EN REVISION'])->default('PENDIENTE');
            $table->string('respuesta_correccion')->nullable()->after('comentario_estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comentario_avance', function (Blueprint $table) {
            $table->dropColumn('comentario_estado');
        });
    }
};
