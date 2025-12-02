<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidatoCurtido extends Model
{
    use HasFactory;

    protected $table = 'candidatos_curtidos';
    protected $fillable = [
        'empresa_id',
        'user_id',
        'vaga_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function vaga()
    {
        return $this->belongsTo(Vaga::class, 'vaga_id');
    }
}
