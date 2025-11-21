<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    use HasFactory;

    /**
     * La clave primaria asociada con la tabla.
     * @var string
     */
    protected $primaryKey = 'rut_alumno';

    /**
     * @var bool
     */
    public $incrementing = false;

    public function proyecto()
    {
        // REALACION PROYECTO ALUMNO
        return $this->hasOne(Proyecto::class, 'alumno_rut', 'rut_alumno');
    }


    public function practicaTutelada()
    {
        // Un Alumno tiene una PracticaTutelada
        return $this->hasOne(PracticaTutelada::class, 'alumno_rut', 'rut_alumno');
    }
};