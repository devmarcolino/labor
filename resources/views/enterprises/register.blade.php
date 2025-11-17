<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Labor for enterprises</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <link rel="shortcut icon" href="../img/lb-blue.svg" type="image/x-icon">
</head>
<body class="bg-white dark:bg-gray-900 transition-colors duration-500">

    <x-loading/>

    

    <div x-data="registrationForm" x-init="init()" class="flex flex-col justify-between mx-auto items-center self-center min-h-screen text-center">
    
    

    <div class="w-full">
        <div class="flex flex-col my-1 sm:gap-5 sm:pt-5">
            <div class="flex justify-between mx-1 items-center gap-12">
                <x-btn-back/>
            </div>

            <div class="mt-1 w-full bg-gray-200 h-1 dark:bg-gray-700 transition-all duration-300">
                <div x-ref="progressBar" class="bg-sky-600 h-1"></div>
            </div>
        </div>

        <form id="registrationForm" x-init="fetchStates(); $watch('selectedState', () => fetchCities())" class="flex flex-col justify-between mx-auto w-full max-w-2xl px-5 py-5 sm:py-9" action="/enterprises/register" method="POST">
            @csrf
            
            <div x-show="step === 1" x-cloak class="flex flex-col gap-3 text-left">
                <div class="text-left mb-6">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Informações</h3>
                    <p class="text-sm text-gray-700 dark:text-gray-400">Precisamos saber mais sobre sua empresa.</p>
                </div>
                <x-input name="nome_empresa" type="text" placeholder="Esse nome aparecerá para os trabalhadores" value="{{ old('nome_empresa') }}" validate-input>
                Nome da empresa
                </x-input>

            </div>

            <div x-show="step === 2" x-cloak class="flex flex-col gap-3 text-left">
                <div class="text-left mb-6">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Adicione seu e-mail</h3>
                    <p class="text-sm text-gray-700 dark:text-gray-400 ">Ele será seu principal meio de login, contato e recuperação de senha.</p>
                </div>

                <x-input name="email" type="email" placeholder="seu@email.com" value="{{ old('email') }}" validate-input>
                    E-mail
                </x-input>

                @error('email')
                <x-warn>{{ $message }}</x-warn>
                @enderror
            </div>

            <div x-show="step === 3" x-cloak class="flex flex-col gap-3 text-left">
                <div class="text-left mb-6">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Adicione seu telefone</h3>
                    <p class="text-sm text-gray-700 dark:text-gray-400">Usaremos seu número para verificações de segurança e para manter sua conta protegida.</p>
                </div>

                <x-input name="telefone" type="tel" placeholder="(00)00000-0000" value="{{ old('telefone') }}" validate-input>
                    Telefone
                </x-input>

                @error('telefone')
                    <x-warn>{{ $message }}</x-warn>
                @enderror
            </div>

            <div x-show="step === 4" x-cloak class="flex flex-col gap-3 text-left">
                <div class="text-left mb-6">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Para sua segurança</h3>
                    <p class="text-sm text-gray-700 dark:text-gray-400">Esses dados são essenciais para você utilizar nossa plataforma.</p>
                </div>

                <x-input name="cnpj" type="text" placeholder="00.000.0000/0000-00" value="{{ old('cnpj') }}" validate-input>
                    CNPJ
                </x-input>   

                @error('cnpj')
                    <x-warn>{{ $message }}</x-warn>
                @enderror
                
                <div>
                    <label for="ramo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Ramo da empresa</label>

                    <select name="ramo" id="ramo" class="bg-gray-50/85 backdrop-blur-md border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700/85 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 placeholder:text-neutral-400 disabled:opacity-50 disabled:bg-gray-200 dark:disabled:bg-gray-800 dark:disabled:text-gray-500">
                        <option selected="">Selecione o ramo de sua empresa</option>
                        <option value="Buffet e festas">Buffet e festas</option>
                        <option  value="Restaurante">Restaurante</option>
                        <option  value="Bar">Bar</option>
                        <option  value="Casa de show">Casa de show</option>
                    </select>
                </div>
            </div>

           

            <div x-show="step === 5" x-cloak class="flex flex-col gap-3 text-left">
            <div class="text-left mb-6">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Crie sua senha de acesso</h3>
                    <p class="text-sm text-gray-700 dark:text-gray-400">Escolha uma senha forte com letras, números e símbolos. Esta será a chave para proteger sua conta.</p>
                </div>                   
            
                <x-input name="password" type="password" placeholder="*******" validate-input>
                Senha
                </x-input>

                <x-input name="password_confirmation" type="password" placeholder="*******" validate-input>
                Confirme sua senha
                </x-input>

                @error('password')
                    <x-warn>{{ $message }}</x-warn>
                @enderror
            </div>
        </form>
    </div>

        <div class="navigation-area mx-auto w-full max-w-2xl px-5 py-5 sm:py-9">
                <div x-show="step === 1">
                    <x-btn-primary x-ref="validateStep1" type="button" @click="step = step + 1" validate-btn>Continuar</x-btn-primary>
                </div>

                <div x-show="[2, 3, 4].includes(step)">
                    <x-btn-outline type="button" @click="step = step - 1" validate-btn>Voltar</x-btn-outline>
                    <x-btn-primary type="button" @click="step = step + 1" validate-btn>Continuar</x-btn-primary>
                </div>

                <div x-show="step === 5">
                    <x-btn-outline type="button" @click="step = step - 1" validate-btn>Voltar</x-btn-outline>
                    <x-btn-primary type="submit" form="registrationForm" validate-btn>Criar conta</x-btn-primary>
                </div>
        </div>
    </div>
</body>
</html>