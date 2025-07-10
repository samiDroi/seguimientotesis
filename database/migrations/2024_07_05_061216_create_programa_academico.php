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
        Schema::create('programa_academico', function (Blueprint $table) {
            $table->bigIncrements('id_programa');
            $table->foreignId('id_unidad')->constrained('unidad_academica','id_unidad');
            $table->string('nombre_programa',70);
            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programa_academico');
    }
};
