<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class End extends Model
{
    use HasFactory;

    protected $table = 'end_tb';

    protected $fillable = [
        'rua',
        'numero',
        'bairro',
        'cidade',
        'uf',
        'cep',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'idEnd');
    }

    public function empresa()
    {
        return $this->hasOne(Empresa::class, 'idEnd');
    }
}
