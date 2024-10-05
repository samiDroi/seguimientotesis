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
        Schema::create('avance_tesis', function (Blueprint $table) {
            $table->bigIncrements('id_avance_tesis');
            $table->foreignId('id_requerimiento')->constrained('comite_tesis_requerimientos','id_requerimiento');
            $table->text('contenido');
            $table->string('estado',40);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avance_tesis');
    }
};
