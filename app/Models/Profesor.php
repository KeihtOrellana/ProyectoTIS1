<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profesor extends Model
{
    use HasFactory;

    /**
     * La clave primaria asociada con la tabla.
     *
     * @var string
     */
    protected $primaryKey = 'rut_profesor';
    protected $table = 'profesores';
    /**
     * Indica si la clave primaria es autoincremental.
     *
     * @var bool
     */
    public $incrementing = false;


    public function proyectosComoGuia()
    {
        return $this->hasMany(Proyecto::class, 'profesor_guia_rut', 'rut_profesor');
    }

    public function proyectosComoComision()
    {
        return $this->hasMany(Proyecto::class, 'profesor_comision_rut', 'rut_profesor');
    }

    public function practicasComoTutor()
    {
       return $this->hasMany(PracticaTutelada::class, 'profesor_tutor_rut', 'rut_profesor');
    }
    public function proyectosCoguia()
    {
        return $this->hasMany(Proyecto::class, 'profesor_coguia_rut', 'rut_profesor');
    }

    
}
