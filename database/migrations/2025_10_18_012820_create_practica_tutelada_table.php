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
    Schema::create('practica_tutelada', function (Blueprint $table) {
        // Clave primaria compuesta (heredada de Alumno)
        $table->unsignedBigInteger('id_habilitacion')->primary();
        $table->integer('alumno_rut')->unsigned();
        $table->string('semestre_inicio', 6);
        $table->string('tipo_proyecto'); // "Pring" o "Prinv"

        // --- CAMPOS COMUNES (Duplicados) ---
        $table->text('descripcion');
        $table->date('fecha_inicio');
        $table->decimal('nota_final', 2, 1)->nullable();
        $table->date('fecha_nota')->nullable();

        // --- CAMPOS ESPECÍFICOS DE PRÁCTICA ---
        $table->string('nombre_empresa', 50);
        $table->string('nombre_supervisor', 100);

        // --- RELACIÓN CON PROFESOR ---
        $table->integer('profesor_tutor_rut')->unsigned();

        $table->timestamps();

        // --- DEFINICIÓN DE LAS RELACIONES ---
        $table->foreign('alumno_rut')->references('rut_alumno')->on('alumnos')->onDelete('cascade');
        $table->foreign('profesor_tutor_rut')->references('rut_profesor')->on('profesores');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('practica_tutelada');
    }
};
