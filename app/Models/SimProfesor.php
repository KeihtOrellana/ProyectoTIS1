<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SimProfesor extends Model
{
    use HasFactory;

    protected $connection = 'mysql_simulacion';
    protected $table = 'sim_profesores';
    protected $primaryKey = 'rut';
    public $incrementing = false;
}