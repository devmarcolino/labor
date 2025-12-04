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
        'horario',
        'gastoTotal',
        'dataCriacao',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'idUser');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'idEmpresa');
    }

    public function vaga()
    {
        return $this->belongsTo(Vaga::class, 'idVaga');
    }
}
