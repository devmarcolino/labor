<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VagaCurtida extends Model
{
    protected $table = 'vaga_curtidas';
    protected $fillable = ['user_id', 'vaga_id'];

    public function vaga()
    {
        return $this->belongsTo(\App\Models\Vaga::class, 'vaga_id');
    }
}
