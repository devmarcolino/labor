<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// ADICIONE ESTAS DUAS LINHAS:
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// MUDE AQUI: de "extends Model" para "extends Authenticatable"
class Empresa extends Authenticatable 
{
    // ADICIONE Notifiable
    use HasFactory, Notifiable; 

    protected $table = 'empresa_tb'; // Verifique o nome da sua tabela

    protected $fillable = [
        'nome_empresa', // ou 'nome_fantasia', use o nome da sua coluna
        'cnpj',
        'tel',
        'email',
        'password',
        'idEnd',
        'desc_empresa',
        'ramo',
        'fotoEmpresa',
        'status',
        // adicione todos os seus campos 'fillable' aqui
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function endereco()
    {
        return $this->belongsTo(End::class, 'idEnd');
    }
}