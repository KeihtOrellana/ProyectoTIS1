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
        // Clave primaria compuesta (heredada de Alumno)
        $table->integer('alumno_rut')->unsigned();
        $table->string('semestre_inicio', 6);
        $table->primary(['alumno_rut', 'semestre_inicio']);

        // Atributo discriminador (para Pring vs Prinv)
        $table->string('tipo_proyecto'); // "Pring" o "Prinv"

        // --- CAMPOS COMUNES (Duplicados) ---
        $table->text('descripcion');
        $table->date('fecha_inicio');
        $table->decimal('nota_final', 2, 1)->nullable();
        $table->date('fecha_nota')->nullable();

        // --- CAMPOS ESPECÍFICOS DE PROYECTO ---
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
