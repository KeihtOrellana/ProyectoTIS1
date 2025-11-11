<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Proyecto extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_habilitacion';
    public $incrementing = false;
    protected $keyType = 'unsignedBigInteger';
    protected $fillable = [
        'alumno_rut',
        'semestre_inicio',
        'tipo_proyecto',
        'descripcion',
        'fecha_inicio',
        'nota_final',
        'fecha_nota',
        'titulo',
        'profesor_guia_rut',
        'profesor_comision_rut',
        'profesor_coguia_rut'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($proyecto) {

            $rutPadded = str_pad($proyecto->alumno_rut, 8, '0', STR_PAD_LEFT);
            $semestreSinGuion = str_replace('-', '', $proyecto->semestre_inicio);
            $proyecto->id_habilitacion = (int)($rutPadded . $semestreSinGuion);
        });
    }
    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'alumno_rut', 'rut_alumno');
    }

    public function profesorGuia()
    {
        return $this->belongsTo(Profesor::class, 'profesor_guia_rut', 'rut_profesor');
    }

    public function profesorComision()
    {
        return $this->belongsTo(Profesor::class, 'profesor_comision_rut', 'rut_profesor');
    }

    public function profesorCoguia()
    {
        return $this->belongsTo(Profesor::class, 'profesor_coguia_rut', 'rut_profesor');
    }
}