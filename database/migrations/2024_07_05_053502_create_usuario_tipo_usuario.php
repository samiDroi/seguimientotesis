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
        Schema::create('usuario_tipo_usuario', function (Blueprint $table) {
            $table->foreignId('id_usuario')->constrained('usuarios','id_user');
            $table->foreignId('id_tipo')->constrained('tipo_usuario','id_tipo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario_tipo_usuario');
    }
};
