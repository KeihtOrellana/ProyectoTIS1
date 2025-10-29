<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SimNota extends Model
{
    use HasFactory;

    protected $connection = 'mysql_simulacion';
    protected $table = 'sim_notas';
}