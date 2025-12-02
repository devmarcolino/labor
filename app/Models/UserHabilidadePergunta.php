<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserHabilidadePergunta extends Model
{
    use HasFactory;
    
    protected $table = 'user_habilidade_perguntas_tb';
    
    // LIBERAR TUDO PARA EVITAR ERRO DE "MASS ASSIGNMENT"
    protected $fillable = [
        'idUser', 
        'idHabilidade', // <--- Essencial
        'idPergunta', 
        'idOpcao'       // <--- Essencial
    ];

    public function opcao()
    {
        return $this->belongsTo(Opcao::class, 'idOpcao');
    }
}