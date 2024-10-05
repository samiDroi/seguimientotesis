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
        Schema::create('tesis_usuarios', function (Blueprint $table) {
            $table->foreignId('id_user')->constrained('usuarios','id_user');
            $table->foreignId('id_tesis')->constrained('tesis','id_tesis');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tesis_usuarios');
    }
};
