<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mensagem extends Model
{
    use HasFactory;

    protected $table = 'mensagem_tb';

    protected $fillable = [
        'remetente_id',
        'destinatario_id',
        'remetente_tipo',     // 'empresa' ou 'usuario'
        'destinatario_tipo',  // 'empresa' ou 'usuario'
        'mensagem',
        'arquivo',
        'horario',
    ];

    protected $casts = [
        'horario' => 'datetime',
    ];

    /**
     * Retorna o remetente (dinâmico: empresa ou usuário)
     */
    public function remetente()
    {
        return $this->morphTo(null, 'remetente_tipo', 'remetente_id');
    }

    /**
     * Retorna o destinatário (dinâmico: empresa ou usuário)
     */
    public function destinatario()
    {
        return $this->morphTo(null, 'destinatario_tipo', 'destinatario_id');
    }
}
