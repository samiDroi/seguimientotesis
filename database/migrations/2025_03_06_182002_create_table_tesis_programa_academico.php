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
        Schema::create('tesis_programa_academico', function (Blueprint $table) {
            $table->unsignedBigInteger('id_tesis');
            $table->unsignedBigInteger('id_programa');

            $table->foreign('id_tesis')->references('id_tesis')->on('tesis')->onDelete('cascade');
            $table->foreign('id_programa')->references('id_programa')->on('programa_academico')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tesis_programa_academico');
    }
};
