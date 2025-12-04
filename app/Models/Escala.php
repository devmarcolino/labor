<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Escala extends Model
{
    protected $table = 'escala_tb';
    protected $fillable = [
        'idUser',
        'idEmpresa',
        'idVaga',
        'dataDiaria',
        'horaDiaria',
        'gastoTotal',
        'dataCriacao',
        'created_at',
        'updated_at',
    ];
}
