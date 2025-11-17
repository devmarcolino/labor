<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // Se a requisição for JSON (API), não redireciona, só retorna null.
        if ($request->expectsJson()) {
            return null;
        }

        // === A MÁGICA ESTÁ AQUI ===
        
        // Se o usuário estava tentando acessar algo em "enterprises/..."
        if ($request->is('enterprises/*')) {
            // Manda ele para a rota de login de EMPRESA
            return route('enterprises.login');
        }

        // Se o usuário estava tentando acessar algo em "workers/..."
        if ($request->is('workers/*')) {
            // Manda ele para a rota de login de TRABALHADOR
            return route('workers.login');
        }

        // === FALBACK (PLANO B) ===
        
        // Se não for nenhum dos dois (ex: uma rota /admin ou /perfil genérica),
        // podemos mandar para a página de "escolha" (/) ou para o login de trabalhador como padrão.
        // Vou usar 'workers.login' como padrão, mas você pode mudar para url('/choose') se preferir.
        return route('workers.login');
    }
}