<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ValidationController extends Controller
{
    /**
     * Checa um campo único (email, username, cpf, etc.)
     */
    public function check(Request $request)
    {
        $field = $request->input('field'); // ex: 'email'
        $value = $request->input('value'); // ex: 'teste@teste.com'
        $type = $request->input('type');   // ex: 'user' ou 'enterprise'

        if (!$field || !$value) {
            return response()->json(['error' => 'Campo ou valor faltando.'], 400);
        }

        $rules = [];

        // Define as regras de validação baseado no campo e tipo
        switch ($field) {
                case 'email':
                    $table = ($type == 'enterprise') ? 'empresa_tb' : 'user_tb';
                    $rules = ['email' => "required|email|unique:{$table},email"];
                    break;
                case 'username':
                    $rules = ['username' => 'required|string|unique:user_tb,username'];
                    break;
                case 'cpf':
                    $rules = ['cpf' => 'required|string|unique:user_tb,cpf'];
                    break;
                case 'cnpj':
                    $rules = ['cnpj' => 'required|string|unique:empresa_tb,cnpj'];
                    break;
                
                // ADICIONE ESTE BLOCO
                case 'telefone':
                    $table = ($type == 'enterprise') ? 'empresa_tb' : 'user_tb';
                    // O campo no banco se chama 'tel', então validamos contra a coluna 'tel'
                    $rules = ['telefone' => "required|string|unique:{$table},tel"];
                    break;
            default:
                // Se for um campo que não precisa checar (ex: nome), só retorna OK
                return response()->json(['valid' => true], 200);
        }

        // Tenta validar
        $validator = Validator::make([$field => $value], $rules);

        if ($validator->fails()) {
            // Se falhar (ex: email já existe), retorna 422 com o erro
            return response()->json($validator->errors(), 422);
        }

        // Se passou, retorna 200 OK
        return response()->json(['valid' => true], 200);
    }
}