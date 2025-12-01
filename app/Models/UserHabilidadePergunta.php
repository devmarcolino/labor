<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserHabilidadePergunta extends Model
{
    use HasFactory;
    
    protected $table = 'user_habilidade_perguntas_tb';
    
    protected $fillable = [
        'idUser', 
        'idHabilidade', 
        'idPergunta', 
        'idOpcao' // <--- Mudou de 'resposta' para 'idOpcao'
    ];

    // Relação para pegar os pontos depois (Útil para o Match)
    public function opcao()
    {
        return $this->belongsTo(Opcao::class, 'idOpcao');
    }
}