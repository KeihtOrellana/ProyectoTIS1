<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SimAlumno extends Model
{
    use HasFactory;
    protected $connection = 'mysql_simulacion'; 
    protected $table = 'sim_alumnos'; 
    protected $primaryKey = 'rut';
    public $incrementing = false;
}