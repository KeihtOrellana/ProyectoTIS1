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
        Schema::connection('mysql_simulacion')->create('sim_notas', function (Blueprint $table) {
        $table->id();
        $table->integer('rut_alumno');
        $table->string('semestre_inscrito', 6);
        $table->decimal('nota_final', 2, 1)->nullable();
        $table->date('fecha_nota')->nullable();
        $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sim_notas');
    }
};
