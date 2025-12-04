<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GastosEmpresas extends Model
{
    protected $table = 'gastos_empresas_tb';

    protected $fillable = [
        'empresa_id',
        'vaga_id',
        'escala_id',
        'funcao',
        'valor',
        'data_confirmacao'
    ];

    // Opcional: Relacionamentos
    public function escala()
    {
        return $this->belongsTo(Escala::class, 'escala_id');
    }
    
    public function empresa()
    {
        return $this->belongsTo(User::class, 'empresa_id');
    }
}