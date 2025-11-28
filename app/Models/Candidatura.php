<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidatura extends Model
{
    protected $table = 'candidaturas_tb';
    protected $fillable = [
        'idUser',
        'idVaga',
        'dataCandidatura',
        'status',
        'nota_ia',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'idUser');
    }

    public function vaga()
    {
        return $this->belongsTo(Vaga::class, 'idVaga');
    }

    // Relação com respostas do formulário
    public function respostas()
    {
        $idHabilidade = $this->vaga ? $this->vaga->funcVaga : null;
        return $this->hasMany(\App\Models\UserHabilidadePergunta::class, 'idUser', 'idUser')
            ->when($idHabilidade, function ($query) use ($idHabilidade) {
                return $query->where('idHabilidade', $idHabilidade);
            });
    }
}
