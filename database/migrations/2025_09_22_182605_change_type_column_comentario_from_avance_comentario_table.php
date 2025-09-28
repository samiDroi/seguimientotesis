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
        Schema::table('comentario_avance', function (Blueprint $table) {
            $table->longText('comentario')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comentario_avance', function (Blueprint $table) {
            $table->string('comentario', 255)->change(); // volvemos a varchar(255)
        });
    }
};
