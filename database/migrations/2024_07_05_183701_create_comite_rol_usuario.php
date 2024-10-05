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
        Schema::create('comite_rol_usuario', function (Blueprint $table) {
            $table->bigIncrements('id_comite_rol');
            $table->foreignId('id_comite')->constrained('comite','id_comite');
            $table->string('nombre_rol');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comite_rol_usuario');
    }
};
