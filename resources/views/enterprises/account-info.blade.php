<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Labor for enterprises</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <link rel="shortcut icon" href="../img/lb-blue.svg" type="image/x-icon">
</head>

<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-500 min-h-screen pb-10">
    
    <x-flash-manager />

    <!-- HEADER -->
    <header class="flex justify-center mx-auto">
        <div class="flex items-center justify-between w-full max-w-2xl pt-1 px-3">
            <x-btn-back/>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white mr-2">Editar <span class="text-sky-600">informações</span></h1>
        </div>
    </header>

    <main class="flex flex-col items-center w-full px-5 pt-4">
        
        <!-- CARD PRINCIPAL (DADOS PESSOAIS) -->
        <div class="w-full max-w-2xl bg-white dark:bg-gray-800 rounded-[40px] shadow-labor p-6 mb-6">
            
            <form action="{{ route('enterprises.account.info.update') }}" method="POST" class="flex flex-col gap-5">
                @csrf
                @method('PATCH')

                <!-- 1. NOME DA EMPRESA -->
                <div>
                    <x-input name="nome_empresa" value="{{ old('nome_empresa', $user->nome_empresa) }}" placeholder="Nome Fantasia">
                        Nome da Empresa
                    </x-input>
                </div>

                <!-- 2. CNPJ (Bloqueado) -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-500 dark:text-gray-400">CNPJ (Não editável)</label>
                    <input type="text" value="{{ $user->cnpj }}" readonly 
                           class="bg-gray-100 border border-gray-200 text-gray-500 text-sm rounded-lg focus:ring-0 focus:border-gray-200 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:text-gray-400">
                </div>

                <!-- 3. EMAIL -->
                <div>
                    <x-input name="email" type="email" value="{{ old('email', $user->email) }}" placeholder="empresa@email.com">
                        E-mail Corporativo
                    </x-input>
                </div>

                <!-- 4. TELEFONE -->
                <div>
                    <x-input id="telInput" name="tel" type="tel" value="{{ old('tel', $user->tel) }}" placeholder="(00) 00000-0000">
                        Telefone / WhatsApp
                    </x-input>
                </div>

                <!-- 5. RAMO DE ATUAÇÃO (Novo) -->
                <div>
                    <x-input name="ramo" value="{{ old('ramo', $user->ramo) }}" placeholder="Ex: Eventos, Buffet, Segurança...">
                        Ramo de Atuação
                    </x-input>
                </div>

                <!-- 6. DESCRIÇÃO DA EMPRESA (Novo - Textarea simples) -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Descrição da Empresa</label>
                    <textarea name="desc_empresa" rows="3" 
                        class="block w-full p-3 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-sky-500 focus:border-sky-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" 
                        placeholder="Fale um pouco sobre a empresa...">{{ old('desc_empresa', $user->desc_empresa) }}</textarea>
                </div>

                <!-- Botão Salvar -->
                <div class="pt-2">
                    <x-btn-primary type="submit" class="w-full justify-center">
                        Salvar Informações
                    </x-btn-primary>
                </div>
            </form>
        </div>


        <!-- SEÇÃO DE SEGURANÇA (SENHA) -->
        <div class="w-full max-w-2xl">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 px-2">Segurança</h3>
            
            <div class="bg-white dark:bg-gray-800 rounded-[40px] shadow-labor p-6">
                <form action="{{ route('enterprises.password.update') }}" method="POST" class="flex flex-col gap-5">
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