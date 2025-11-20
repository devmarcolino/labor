<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http; // Importante para chamar a API

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
        'latitude',  // Adicionado na etapa anterior
        'longitude', // Adicionado na etapa anterior
    ];

    /**
     * O "booted" é executado sempre que o Model é iniciado.
     * Aqui definimos os "ganchos" (hooks) automáticos.
     */
    protected static function booted()
    {
        // Evento "saving": Roda ANTES de salvar (seja criar ou atualizar)
        static::saving(function ($endereco) {
            
            // Verifica se algum campo relevante do endereço mudou
            // (Para não chamar a API à toa se só mudou o número, por exemplo)
            if ($endereco->isDirty(['rua', 'numero', 'cidade', 'uf', 'cep'])) {
                
                // 1. Monta o endereço completo numa string
                $query = "{$endereco->rua}, {$endereco->numero}, {$endereco->bairro}, {$endereco->cidade} - {$endereco->uf}, Brazil";
                
                try {
                    // 2. Chama a API do Nominatim (OpenStreetMap)
                    // O User-Agent é obrigatório pela política deles
                    $response = Http::withHeaders([
                        'User-Agent' => 'LaborApp/1.0 (contato@seusite.com)' 
                    ])->get('https://nominatim.openstreetmap.org/search', [
                        'q' => $query,
                        'format' => 'json',
                        'limit' => 1
                    ]);

                    // 3. Se achou, pega a Lat/Long
                    if ($response->successful() && !empty($response->json())) {
                        $data = $response->json()[0];
                        $endereco->latitude = $data['lat'];
                        $endereco->longitude = $data['lon'];
                    } else {
                        // Se não achou, deixa nulo (ou tenta buscar só pelo CEP como fallback)
                        $endereco->latitude = null;
                        $endereco->longitude = null;
                    }

                } catch (\Exception $e) {
                    // Se der erro de conexão, segue o jogo sem travar o cadastro
                    // (Em produção, você poderia logar esse erro)
                }
            }
        });
    }

    public function user()
    {
        return $this->hasOne(User::class, 'idEnd');
    }

    public function empresa()
    {
        return $this->hasOne(Empresa::class, 'idEnd');
    }
}