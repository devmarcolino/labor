<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pergunta extends Model
{
    protected $table = 'perguntas_tb';
    protected $fillable = ['idHabilidade', 'texto', 'tipo'];

    public function opcoes()
    {
        return $this->hasMany(Opcao::class, 'idPergunta');
    }
}
