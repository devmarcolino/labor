<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Opcao extends Model
{
    protected $table = 'opcoes_tb';
    protected $fillable = ['idPergunta', 'texto', 'pontos'];
}