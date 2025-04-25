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
        Schema::table('usuarios_comite_roles', function (Blueprint $table) {
            $table->dropForeign(['id_user_creador']);

            // Luego la creamos correctamente
            $table->foreign('id_user_creador')->references('id_user')->on('usuarios');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usuarios_comite_roles', function (Blueprint $table) {
            $table->dropForeign(['id_user_creador']);

            // Revertir a la anterior si es necesario (opcional)
            $table->foreign('id_user_creador')->references('id_user')->on('usuarios_comite');
        });
    }
};
