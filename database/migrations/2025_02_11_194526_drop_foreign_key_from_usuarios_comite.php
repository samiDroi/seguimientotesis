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
        Schema::table('usuarios_comite', function (Blueprint $table) {
            $table->dropForeign('usuarios_comite_id_comite_rol_foreign');
            $table->dropColumn('id_comite_rol');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usuarios_comite', function (Blueprint $table) {
            //
        });
    }
};
