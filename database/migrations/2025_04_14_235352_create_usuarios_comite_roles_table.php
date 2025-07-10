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
        Schema::create('usuarios_comite_roles', function (Blueprint $table) {
            // $table->id();
            $table->foreignId('id_usuario_comite')->constrained('usuarios_comite','id_usuario_comite');
            $table->foreignId('id_rol')->constrained('roles','id_rol');
            $table->foreignId('id_user_creador')->constrained('usuarios_comite','id_user');
                        $table->softDeletes();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios_comite_roles');
    }
};
