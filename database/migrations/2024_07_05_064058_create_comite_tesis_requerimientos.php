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
        Schema::create('comite_tesis_requerimientos', function (Blueprint $table) {
            $table->bigIncrements('id_requerimiento');
            $table->foreignId('id_tesis_comite')->constrained('tesis_comite','id_tesis_comite');
            $table->string('nombre_requerimiento');
            $table->text('descripcion');
            $table->text('motivo_rechazo')->nullable();
                        $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comite_tesis_requerimientos');
    }
};
