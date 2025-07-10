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
        Schema::create('plan_trabajo', function (Blueprint $table) {
            $table->bigIncrements('id_plan'); // cambia el nombre del id
            $table->unsignedBigInteger('id_tesis_comite');
            $table->text('objetivo');
            $table->text('metas');
            $table->text('criterios');
            $table->text('compromisos');

            // Clave foránea
            $table->foreign('id_tesis_comite')
                ->references('id_tesis_comite')
                ->on('tesis_comite')
                ->onDelete('cascade');
                            $table->softDeletes();

            $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_trabajo');
    }
};
