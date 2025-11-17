<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    // Define o nome da tabela correta
    protected $table = 'habilidades_tb'; 

    // Ajuste conforme a coluna de nome na sua tabela habilidades_tb
    // (Ex: 'nome', 'descricao', 'titulo')
    protected $fillable = ['nome']; 
}