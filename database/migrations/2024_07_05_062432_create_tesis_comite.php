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
        Schema::create('tesis_comite', function (Blueprint $table) {
            $table->bigIncrements('id_tesis_comite');
            $table->foreignId('id_tesis')->constrained('tesis','id_tesis');
            $table->foreignId('id_comite')->constrained('comite','id_comite');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tesis_comite');
    }
};
