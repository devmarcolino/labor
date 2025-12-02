<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Editar Informações</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <link rel="shortcut icon" href="../img/lb-blue.svg" type="image/x-icon">
</head>

<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-500 min-h-screen pb-10">
    
    <x-flash-manager />

    <!-- HEADER -->
    <header class="flex justify-center w-full mx-auto pt-4 mb-2">
        <div class="flex items-center justify-between w-full max-w-2xl px-5 relative">
            <a href="{{ route('workers.account') }}" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-800 transition-colors">
                <svg class="w-6 h-6 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            
            <h1 class="text-xl font-bold text-center dark:text-white">Editar informações</h1>
        </div>
    </header>

    <main class="flex flex-col items-center w-full px-5 pt-4">
        
        <!-- CARD PRINCIPAL (DADOS PESSOAIS) -->
        <div class="w-full max-w-2xl bg-white dark:bg-gray-800 rounded-[40px] shadow-labor p-6 mb-6">
            
            <form action="{{ route('workers.account.info.update') }}" method="POST" class="flex flex-col gap-5">
                @csrf
                @method('PATCH')

                <!-- 1. USUÁRIO (Editável) -->
                <div>
                    <x-input name="username" 
        value="{{ old('username', $user->username) }}" 
        placeholder="@usuario"
        x-on:input="if (!$el.value.startsWith('@')) $el.value = '@' + $el.value.replace(/@/g, '')">
                        Usuário
                    </x-input>
                </div>

                <!-- 2. NOME (Bloqueado) -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-500 dark:text-gray-400">Nome Completo (Não editável)</label>
                    <input type="text" value="{{ $user->nome_real }}" readonly 
                           class="bg-gray-100 border border-gray-200 text-gray-500 text-sm rounded-lg focus:ring-0 focus:border-gray-200 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:text-gray-400">
                </div>

                <!-- 3. EMAIL (Editável) -->
                <div>
                    <x-input name="email" type="email" value="{{ old('email', $user->email) }}" placeholder="seu@email.com">
                        E-mail
                    </x-input>
                </div>

                <!-- 4. TELEFONE (Editável) -->
                <div>
                    <x-input id="telInput" name="tel" type="tel" value="{{ old('tel', $user->tel) }}" placeholder="(00) 00000-0000" x-mask="(99) 99999-9999">
                        Telefone
                    </x-input>
                </div>

                <!-- 5. CPF (Bloqueado) -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-500 dark:text-gray-400">CPF (Não editável)</label>
                    <input type="text" value="{{ $user->cpf }}" readonly 
                           class="bg-gray-100 border border-gray-200 text-gray-500 text-sm rounded-lg focus:ring-0 focus:border-gray-200 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:text-gray-400">
                </div>

                <!-- 6. DATA NASCIMENTO (Bloqueado) -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-500 dark:text-gray-400">Data de Nascimento (Não editável)</label>
                    <input type="text" value="{{ \Carbon\Carbon::parse($user->datanasc)->format('d/m/Y') }}" readonly 
                           class="bg-gray-100 border border-gray-200 text-gray-500 text-sm rounded-lg focus:ring-0 focus:border-gray-200 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:text-gray-400">
                </div>

                <!-- Botão Salvar -->
                <div class="pt-2">
                    <x-btn-primary type="submit" class="w-full justify-center">
                        Confirmar mudanças
                    </x-btn-primary>
                </div>
            </form>
        </div>


        <!-- SEÇÃO DE SEGURANÇA (SENHA) -->
        <div class="w-full max-w-2xl">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 px-2">Segurança</h3>
            
            <div class="bg-white dark:bg-gray-800 rounded-[40px] shadow-labor p-6">
                <form action="{{ route('workers.password.update') }}" method="POST" class="flex flex-col gap-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input name="current_password" type="password" placeholder="Sua senha atual">
                            Senha Atual
                        </x-input>
                        @error('current_password', 'updatePassword')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <x-input name="password" type="password" placeholder="Nova senha">
                            Nova Senha
                        </x-input>
                        @error('password', 'updatePassword')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <x-input name="password_confirmation" type="password" placeholder="Confirme a nova senha">
                            Confirmar Nova Senha
                        </x-input>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full py-3 rounded-full border border-gray-300 text-gray-700 font-semibold hover:bg-gray-50 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700 transition-colors">
                            Alterar Senha
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </main>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const telEl = document.getElementById('telInput');
        if (telEl) {
            IMask(telEl, { mask: '(00) 00000-0000' });
        }
    });
</script>
</body>
</html>