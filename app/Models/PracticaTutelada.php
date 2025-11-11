<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PracticaTutelada extends Model
{
    use HasFactory;
    protected $table = 'practica_tutelada';
    protected $primaryKey = 'id_habilitacion';
    public $incrementing = false;
    protected $keyType = 'unsignedBigInteger';
    protected $fillable = [
        'alumno_rut',
        'semestre_inicio',
        'descripcion',
        'fecha_inicio',
        'nota_final',
        'fecha_nota',
        'nombre_empresa',
        'nombre_supervisor',
        'profesor_tutor_rut'
    ];


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($practica) {
            $rutPadded = str_pad($practica->alumno_rut, 8, '0', STR_PAD_LEFT);
            $semestreSinGuion = str_replace('-', '', $practica->semestre_inicio);
            $practica->id_habilitacion = (int)($rutPadded . $semestreSinGuion);
        });
    }
    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'alumno_rut', 'rut_alumno');
    }

    public function profesorTutor()
    {
        return $this->belongsTo(Profesor::class, 'profesor_tutor_rut', 'rut_profesor');
    }
}