<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
// IMPORTE AS NOVAS REGRAS
use App\Rules\ValidaCpf;
use App\Rules\ValidaCnpj;

class ValidationController extends Controller
{
    public function check(Request $request)
    {
        $field = $request->input('field');
        $value = $request->input('value');
        $type  = $request->input('type', 'user');

        if (!$field || !$value) {
            return response()->json(['valid' => true]);
        }

        $table = ($type === 'enterprise') ? 'empresa_tb' : 'user_tb';
        
        // Regras Básicas
        $rules = [];

        switch ($field) {
            case 'email':
                // Valida formato real de email E se é único
                $rules = [$field => ["required", "email:rfc,dns", "unique:{$table},email"]];
                break;
            
            case 'cpf':
                // Valida algoritmo de CPF E se é único
                $rules = [$field => ["required", new ValidaCpf, "unique:user_tb,cpf"]];
                break;

            case 'cnpj':
                // Valida algoritmo de CNPJ E se é único
                $rules = [$field => ["required", new ValidaCnpj, "unique:empresa_tb,cnpj"]];
                break;

            case 'telefone':
                // Regex para: (11) 99999-9999 ou (11) 9999-9999
                // Formato: Parenteses, Espaço, Hífen
                $rules = [$field => [
                    "required", 
                    "unique:{$table},tel",
                    "regex:/^\(\d{2}\) \d{4,5}-\d{4}$/" 
                ]];
                break;

            case 'username':
                $rules = [$field => ["required", "string", "unique:user_tb,username"]];
                break;
        }

        if (empty($rules)) {
            return response()->json(['valid' => true]);
        }

        // Faz a validação
        $validator = Validator::make([$field => $value], $rules, [
            'email.email' => 'O formato do e-mail é inválido.',
            'telefone.regex' => 'O telefone deve estar no formato (99) 99999-9999.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'valid' => false,
                'message' => $validator->errors()->first($field)
            ], 422);
        }

        return response()->json(['valid' => true]);
    }
}