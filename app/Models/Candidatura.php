<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidatura extends Model
{
    protected $table = 'candidaturas_tb';
    protected $fillable = [
        'idUser',
        'idVaga',
        'dataCandidatura',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'idUser');
    }

    public function vaga()
    {
        return $this->belongsTo(Vaga::class, 'idVaga');
    }
}
