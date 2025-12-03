<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Escala extends Model
{
    protected $table = 'escala_tb';
    protected $fillable = [
        'user_id',
        'empresa_id',
        'created_at',
        'updated_at',
    ];
}
