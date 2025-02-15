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
        Schema::table('comite_rol_usuario', function (Blueprint $table) {
            $table->dropForeign(['id_comite']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comite_rol_usuario', function (Blueprint $table) {
            $table->dropForeign('id_comite');
            $table->dropColumn('id_comite');

        });
    }
};
