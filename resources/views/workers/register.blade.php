<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Labor for workers</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <link rel="shortcut icon" href="../img/lb-blue.svg" type="image/x-icon">
</head>
<x-flash-manager />
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

        <form id="registrationForm" x-init="fetchStates(); $watch('selectedState', () => fetchCities())" class="flex flex-col justify-between mx-auto w-full max-w-2xl px-5 py-5 sm:py-9" action="/workers/register" method="POST">
            @csrf
            
            <div x-show="step === 1" x-transition x-cloak class="flex flex-col gap-3 text-left">
                <div class="text-left mb-6">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Informações pessoais</h3>
                    <p class="text-sm text-gray-700 dark:text-gray-400">Precisamos saber mais sobre você.</p>
                </div>

                <div @blur.capture="validateField('nome_real', 'user')">
                    <x-input name="nome_real" x-model="fields.nome_real" type="text" placeholder="Insira seu nome completo" value="{{ old('nome_real') }}" validate-input>
                    Nome Completo
                    </x-input>
                </div>
 
                <div @blur.capture="validateField('username', 'user')">
                    <x-input x-model="fields.username" name="username" type="text" placeholder="Crie seu @" value="{{ old('username') }}" validate-input>
                    Usuário
                    </x-input>
                </div>
                
            </div>

            <div x-show="step === 2" x-transition x-cloak class="flex flex-col gap-3 text-left">
                <div class="text-left mb-6">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Adicione seu e-mail</h3>
                    <p class="text-sm text-gray-700 dark:text-gray-400 ">Ele será seu principal meio de login, contato e recuperação de senha.</p>
                </div>

                <div @blur.capture="validateField('email', 'user')">
                    <x-input x-model="fields.email" name="email" type="email" placeholder="Crie seu @" value="{{ old('email') }}" validate-input>
                    E-Mail
                    </x-input>
                </div>
            </div>

            <div x-show="step === 3" x-transition x-cloak class="flex flex-col gap-3 text-left">
                <div class="text-left mb-6">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Adicione seu telefone</h3>
                    <p class="text-sm text-gray-700 dark:text-gray-400">Usaremos seu número para verificações de segurança e para manter sua conta protegida.</p>
                </div>

                <div @blur.capture="validateField('telefone', 'user')">
                    <x-input x-model="fields.telefone" name="telefone" type="tel" placeholder="(00)00000-0000" value="{{ old('telefone') }}" validate-input>
                        Telefone
                    </x-input>
                </div>

            </div>

            <div x-show="step === 4" x-transition x-cloak class="flex flex-col gap-3 text-left">
                <div class="text-left mb-6">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Para sua segurança</h3>
                    <p class="text-sm text-gray-700 dark:text-gray-400">Esses dados são essenciais para a segurança do seu perfil e para validar suas candidaturas futuras.</p>
                </div>

                <x-input x-model="fields.datanasc" type="text" name="datanasc" datepicker datepicker-format="dd/mm/yyyy" placeholder="00/00/0000" value="{{ old('datanasc') }}"  validate-input>
                Data de Nascimento
                <x-slot:icon>
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4Z"/><path d="M0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                        </svg>
                    </x-slot:icon>
                </x-input>

                <div @blur.capture="validateField('cpf', 'user')">
                    <x-input x-model="fields.cpf" name="cpf" type="text" placeholder="000.000.000-00" value="{{ old('cpf') }}" validate-input>
                        CPF
                    </x-input> 
                </div>  

            </div>

           

            <div x-show="step === 5" x-transition x-cloak class="flex flex-col gap-3 text-left">
            <div class="text-left mb-6">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Crie sua senha de acesso</h3>
                    <p class="text-sm text-gray-700 dark:text-gray-400">Escolha uma senha forte com letras, números e símbolos. Esta será a chave para proteger sua conta.</p>
                </div>  

                <div>
                <div @blur.capture="validateField('password', 'user')">
                    <x-input x-model="fields.password" name="password" type="password" placeholder="*******" validate-input>
                    Senha
                    </x-input>
                </div>
                <p x-show="errors.password" x-text="errors.password" class="text-xs text-red-500 mt-1"></p>
                </div>
                

                <div>
                <div @blur.capture="validateField('password_confirmation', 'user')">
                <x-input x-model="fields.password_confirmation" name="password_confirmation" type="password" placeholder="*******" validate-input>
                Confirme sua senha
                </x-input>
                </div>
                <p x-show="errors.password_confirmation" x-text="errors.password_confirmation" class="text-xs text-red-500 mt-1"></p>
                </div>

            </div>
        </form>
    </div>

       <div class="navigation-area mx-auto w-full max-w-2xl px-5 py-5 sm:py-9 flex flex-col gap-1">
        
        {{-- BOTÃO VOLTAR (Só aparece se step > 1) --}}
        <div x-show="step > 1">
            <x-btn-outline type="button" 
                           @click="step--" 
                           class="w-full">
                Voltar
            </x-btn-outline>
        </div>

        {{-- BOTÃO CONTINUAR (Aparece steps 1 a 4) --}}
        <template x-if="step < 5">
            <x-btn-primary type="button" 
                           @click="step++" 
                           x-bind:disabled="isStepInvalid" 
                           class="w-full">
                Continuar
            </x-btn-primary>
        </template>

        {{-- BOTÃO CRIAR CONTA (Aparece step 5) --}}
        <template x-if="step === 5">
            <x-btn-primary type="submit" 
                           form="registrationForm"
                           x-bind:disabled="isStepInvalid"
                           class="w-full bg-sky-600 hover:bg-sky-700">
                Criar Conta
            </x-btn-primary>
        </template>
        
    </div>
</div>
    </div>
</body>
</html>