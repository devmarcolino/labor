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
        'horario',
        'funcVaga',
        'imgVaga',
    ];

    protected $casts = [
        'dataVaga' => 'date',
        'valor_vaga' => 'decimal:2',
    ];

    // Relacionamento: Vaga tem muitas Candidaturas
    public function candidaturas()
    {
        return $this->hasMany(\App\Models\Candidatura::class, 'idVaga');
    }

    // Relacionamento: Vaga pertence a uma Empresa
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'idEmpresa');
    }

    // Contador de visualizações
    public function visualizacoesCount()
    {
        return \DB::table('visualizacao_vaga')
            ->where('idVaga', $this->id)
            ->count();
    }

    // Contador de candidaturas
    public function candidaturasCount()
    {
        return $this->candidaturas()->count();
    }
}
