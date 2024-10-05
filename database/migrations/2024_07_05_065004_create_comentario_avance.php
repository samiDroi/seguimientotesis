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
        Schema::create('comentario_avance', function (Blueprint $table) {
            $table->foreignId('id_avance_tesis')->constrained('avance_tesis','id_avance_tesis');
            $table->foreignId('id_user')->constrained('usuarios','id_user');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comentario_avance');
    }
};
