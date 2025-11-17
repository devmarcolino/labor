<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ValidationController extends Controller
{
    public function check(Request $request)
    {
        $field = $request->input('field');
        $value = $request->input('value');
        $type  = $request->input('type', 'user'); // 'user' ou 'enterprise'

        if (!$field || !$value) {
            return response()->json(['valid' => true]); // Ignora campos vazios (o front cuida disso)
        }

        $table = ($type === 'enterprise') ? 'empresa_tb' : 'user_tb';
        
        // Mapeamento: Nome do campo no Front => Nome da coluna no Banco
        $columnMap = [
            'email'    => 'email',
            'username' => 'username', // Só user tem
            'cpf'      => 'cpf',      // Só user tem
            'cnpj'     => 'cnpj',     // Só empresa tem
            'telefone' => 'tel',      // Front manda 'telefone', banco é 'tel'
        ];

        if (!array_key_exists($field, $columnMap)) {
            return response()->json(['valid' => true]);
        }

        $dbColumn = $columnMap[$field];
        $rules = [$field => "unique:{$table},{$dbColumn}"];

        $validator = Validator::make([$field => $value], $rules);

        if ($validator->fails()) {
            // Retorna a primeira mensagem de erro
            return response()->json([
                'valid' => false,
                'message' => $validator->errors()->first($field)
            ], 422);
        }

        return response()->json(['valid' => true]);
    }
}