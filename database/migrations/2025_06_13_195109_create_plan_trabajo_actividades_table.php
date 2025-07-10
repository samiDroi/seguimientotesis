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
        Schema::create('plan_trabajo_actividades', function (Blueprint $table) {
            $table->bigIncrements('id_actividad');
            $table->unsignedBigInteger('id_plan');

            $table->string('tema',300);
            $table->text('descripcion');
            $table->date('fecha_entrega');
            $table->foreign('id_plan')
                ->references('id_plan')
                ->on('plan_trabajo')
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
        Schema::dropIfExists('plan_trabajo_actividades');
    }
};
