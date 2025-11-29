<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pergunta extends Model
{
    use HasFactory;
    protected $table = 'perguntas_tb';
    protected $fillable = ['idHabilidade', 'texto', 'tipo', 'opcoes'];

    // Adiciona helper para atualizar texto das perguntas
    public static function atualizarTextoParaSelect()
    {
        $perguntas = self::all();
        foreach ($perguntas as $p) {
            if (strpos($p->texto, 'Selecione:') === false) {
                $p->texto = $p->texto . ' (Selecione: Ruim, Regular, Bom, Ótimo, Excelente)';
                $p->save();
            }
        }
    }
}
