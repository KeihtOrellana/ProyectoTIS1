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
    Schema::create('proyectos', function (Blueprint $table) {
        $table->unsignedBigInteger('id_habilitacion')->primary();
        $table->integer('alumno_rut')->unsigned();
        $table->string('semestre_inicio', 6);
        $table->string('tipo_proyecto'); // "Pring" o "Prinv"

        // --- CAMPOS COMUNES 
        $table->text('descripcion');
        $table->date('fecha_inicio');
        $table->decimal('nota_final', 2, 1)->nullable();
        $table->date('fecha_nota')->nullable();
        $table->string('titulo', 80);

        // --- RELACIONES CON PROFESORES ---
        $table->integer('profesor_guia_rut')->unsigned();
        $table->integer('profesor_comision_rut')->unsigned();
        $table->integer('profesor_coguia_rut')->unsigned()->nullable();
        
        $table->timestamps();

        // --- DEFINICIÓN DE LAS RELACIONES ---
        $table->foreign('alumno_rut')->references('rut_alumno')->on('alumnos')->onDelete('cascade');
        $table->foreign('profesor_guia_rut')->references('rut_profesor')->on('profesores');
        $table->foreign('profesor_comision_rut')->references('rut_profesor')->on('profesores');
        $table->foreign('profesor_coguia_rut')->references('rut_profesor')->on('profesores');
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proyectos');
    }
};
