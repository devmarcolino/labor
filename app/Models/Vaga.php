<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vaga extends Model
{
    use HasFactory;

    protected $table = 'vagas_tb';

    protected $fillable = [
        'idEmpresa',
        'tipoVaga',
        'valor_vaga',
        'dataVaga',
        'descVaga',
        'funcVaga',
        'imgVaga',
    ];

    protected $casts = [
        'dataVaga' => 'date',
        'valor_vaga' => 'decimal:2',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'idEmpresa');
    }
}
