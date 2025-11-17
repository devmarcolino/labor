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

    

    <div x-data="enterpriseForm" x-init="init()" class="flex flex-col justify-between mx-auto items-center self-center min-h-screen text-center">
    
    <div class="w-full">
        <div class="flex flex-col my-1 sm:gap-5 sm:pt-5">
            <div class="flex justify-between mx-1 items-center gap-12">
                <x-btn-back/>
            </div>
            <div class="mt-1 w-full bg-gray-200 h-1 dark:bg-gray-700 transition-all duration-300">
                <div x-ref="progressBar" class="bg-sky-600 h-1" style="width: 20%"></div>
            </div>
        </div>

        <form id="registrationForm" class="flex flex-col justify-between mx-auto w-full max-w-2xl px-5 py-5 sm:py-9" action="/enterprises/register" method="POST">
            @csrf
            
            <div x-show="step === 1" x-transition class="flex flex-col gap-3 text-left">
                <div class="text-left mb-6">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Dados da Empresa</h3>
                    <p class="text-sm text-gray-700 dark:text-gray-400">Comece com o básico.</p>
                </div>

                <div>
                    <x-input x-model="fields.nome_empresa" name="nome_empresa" type="text" placeholder="Nome Fantasia">
                        Nome da Empresa
                    </x-input>
                </div>

                <div>
                    <label for="ramo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Ramo da empresa</label>

                    <select x-model="fields.ramo" name="ramo" id="ramo" class="bg-gray-50/85 backdrop-blur-md border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700/85 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 placeholder:text-neutral-400 disabled:opacity-50 disabled:bg-gray-200 dark:disabled:bg-gray-800 dark:disabled:text-gray-500">
                        <option selected="">Selecione o ramo de sua empresa</option>
                        <option value="Buffet e festas">Buffet e festas</option>
                        <option  value="Restaurante">Restaurante</option>
                        <option  value="Bar">Bar</option>
                        <option  value="Casa de show">Casa de show</option>
                    </select>
                </div>
            </div>

            <div x-show="step === 2" x-transition class="flex flex-col gap-3 text-left">
                <div class="text-left mb-6">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Email Corporativo</h3>
                    <p class="text-sm text-gray-700 dark:text-gray-400">Para login e contato.</p>
                </div>

                <div>
                    <div @blur.capture="validateField('email', 'enterprise')">
                        <x-input x-model="fields.email" name="email" type="email" placeholder="empresa@email.com">
                            E-mail
                        </x-input>
                    </div>
                    <p x-show="isChecking.email" class="text-sm text-blue-500 mt-1">Verificando...</p>
                    <template x-if="errors.email">
                        <x-warn><span x-text="errors.email"></span></x-warn>
                    </template>
                </div>
            </div>

            <div x-show="step === 3" x-transition class="flex flex-col gap-3 text-left">
                <div class="text-left mb-6">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Contato</h3>
                </div>

                <div>
                    <div @blur.capture="validateField('telefone', 'enterprise')">
                        <x-input x-model="fields.telefone" name="telefone" type="tel" placeholder="(00) 00000-0000">
                            Telefone / WhatsApp
                        </x-input>
                    </div>
                    <p x-show="isChecking.telefone" class="text-sm text-blue-500 mt-1">Verificando...</p>
                    <template x-if="errors.telefone">
                        <x-warn><span x-text="errors.telefone"></span></x-warn>
                    </template>
                </div>
            </div>

            <div x-show="step === 4" x-transition class="flex flex-col gap-3 text-left">
                <div class="text-left mb-6">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Documentação</h3>
                    <p class="text-sm text-gray-700 dark:text-gray-400">Insira o CNPJ da empresa.</p>
                </div>

                <div>
                    <div @blur.capture="validateField('cnpj', 'enterprise')">
                        <x-input x-model="fields.cnpj" name="cnpj" type="text" placeholder="00.000.000/0000-00">
                            CNPJ
                        </x-input>
                    </div>
                    <p x-show="isChecking.cnpj" class="text-sm text-blue-500 mt-1">Verificando...</p>
                    <template x-if="errors.cnpj">
                        <x-warn><span x-text="errors.cnpj"></span></x-warn>
                    </template>
                </div>
            </div>

            <div x-show="step === 5" x-transition class="flex flex-col gap-3 text-left">
                <div class="text-left mb-6">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Segurança</h3>
                    <p class="text-sm text-gray-700 dark:text-gray-400">Crie uma senha forte.</p>
                </div>

                <x-input x-model="fields.password" name="password" type="password" placeholder="*******">
                    Senha
                </x-input>

                <x-input x-model="fields.password_confirmation" name="password_confirmation" type="password" placeholder="*******">
                    Confirmar Senha
                </x-input>
            </div>

        </form>
    </div>

    <div class="mt-6 mx-auto w-full max-w-2xl px-5 py-5 sm:py-9 flex flex-col gap-4">
        
        <div x-show="step > 1">
            <x-btn-outline type="button" @click="step--" class="w-full">
                Voltar
            </x-btn-outline>
        </div>

        <template x-if="step < 5">
            <x-btn-primary type="button" 
                           @click="step++" 
                           x-bind:disabled="isStepInvalid" 
                           class="w-full">
                Continuar
            </x-btn-primary>
        </template>

        <template x-if="step === 5">
            <x-btn-primary type="submit" 
                           form="registrationForm"
                           x-bind:disabled="isStepInvalid"
                           class="w-full bg-green-600 hover:bg-green-700">
                Criar Conta Empresarial
            </x-btn-primary>
        </template>
        
    </div>
</div>
</body>
</html>