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
        Schema::table('comite', function (Blueprint $table) {
            $table->enum('rol',['DIRECTOR','CODIRECTOR','ASESOR','TUTOR']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comite', function (Blueprint $table) {
            //
        });
    }
};
