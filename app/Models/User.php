<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// REMOVA a linha "use Illuminate\Database\Eloquent\Model;"
// ADICIONE estas duas linhas:
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Skill;

// MUDE AQUI: de "extends Model" para "extends Authenticatable"
class User extends Authenticatable
{
    // ADICIONE Notifiable aqui\\
    use HasFactory, Notifiable; 

    protected $table = 'user_tb';

    protected $fillable = [
        'username',
        'email',
        'cpf',
        'datanasc',
        'tel',       // Verifique se no seu controller você está salvando como 'tel'
        'password',
        'fotoUser',
        'status',
        'nome_real',
        'idEnd',
    ];

    protected $hidden = [
        'password',
        'remember_token', // É bom adicionar isso
    ];

    public function endereco()
    {
        return $this->belongsTo(End::class, 'idEnd');
    }

    public function skills()
    {
        // O Laravel procura a tabela 'skill_user' (nomes em ordem alfabética)
        // Se sua tabela pivo tiver outro nome, ajuste aqui
        return $this->belongsToMany(Skill::class, 'skill_user');
    }
}