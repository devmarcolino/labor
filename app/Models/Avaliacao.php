<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Avaliacao extends Model
{
    use HasFactory;

    // Importante: Define o nome exato da sua tabela
    protected $table = 'avaliacoes_tb';

    // Campos que podem ser salvos pelo Controller
    protected $fillable = [
        'id_avaliador',
        'id_avaliado',
        'escala_id',
        'nota',
        'comentario',
        'tipo_avaliacao'
    ];

    // --- Relacionamentos (Opcional, mas bom ter pronto) ---

    // Quem fez a avaliação (Empresa)
    public function avaliador()
    {
        return $this->belongsTo(Empresa::class, 'id_avaliador');
    }

    // Quem recebeu a avaliação (Freelancer)
    public function avaliado()
    {
        return $this->belongsTo(User::class, 'id_avaliado');
    }

    // A qual escala isso pertence
    public function escala()
    {
        return $this->belongsTo(Escala::class, 'escala_id');
    }
}