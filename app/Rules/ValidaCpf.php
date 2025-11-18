<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidaCpf implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Remove caracteres não numéricos
        $c = preg_replace('/\D/', '', $value);

        // Verifica se tem 11 dígitos ou se todos são iguais (ex: 111.111.111-11)
        if (strlen($c) != 11 || preg_match("/^{$c[0]}{11}$/", $c)) {
            $fail('O CPF informado é inválido.');
            return;
        }

        // Cálculo dos Dígitos Verificadores
        for ($s = 10, $n = 0, $i = 0; $s >= 2; $n += $c[$i++] * $s--);
        if ($c[9] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
            $fail('O CPF informado é inválido.');
            return;
        }

        for ($s = 11, $n = 0, $i = 0; $s >= 2; $n += $c[$i++] * $s--);
        if ($c[10] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
            $fail('O CPF informado é inválido.');
        }
    }
}