<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Labor for workers</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>
<body class="bg-white dark:bg-gray-900 transition-colors duration-500">

    <x-loading/>

    <div class="flex flex-col min-h-screen">

    <div class="flex flex-col mt-2 sm:gap-3 sm:pt-5">
     <div class="flex justify-between mx-1 items-center gap-12">
            <x-btn-back/>
    </div>

      <div class="my-2 w-full bg-gray-200 h-1 dark:bg-gray-700">
          <div class="bg-sky-600 h-1" style="width: 10%"></div>
      </div>
    </div>

    <div class="flex flex-col justify-center">
     
    <div class="mt-5 px-5 sm:mx-auto sm:w-full sm:max-w-sm">
        <form x-data="registrationForm" x-init="fetchStates(); $watch('selectedState', () => fetchCities())" class="flex flex-col justify-between" action="{{ route('register') }}" method="POST">
            @csrf
            
            <div x-show="step === 1" x-cloak class="flex flex-col gap-3">
                <div class="text-left mb-6">
                    <h3 class="text-2xl font-bold text-gray-900">Informações pessoais</h3>
                    <p class="text-sm text-gray-700">Precisamos saber mais sobre você.</p>
                </div>
                <x-input name="nome" type="text" placeholder="Insira seu nome completo" value="{{ old('nome') }}" validate-input>
                Nome Completo
                </x-input>
 
                <x-input name="user" type="text" placeholder="Crie seu @" value="{{ old('user') }}" validate-input>
                Usuário
                </x-input>
                
                @error('user')
                    <x-warn>{{ $message }}</x-warn>
                @enderror
            </div>

            <div x-show="step === 2" x-cloak class="flex flex-col gap-3">
                <div class="text-left mb-6">
                    <h3 class="text-2xl font-bold text-gray-900">Adicione seu e-mail</h3>
                    <p class="text-sm text-gray-700">Ele será seu principal meio de login, contato e recuperação de senha.</p>
                </div>

                <x-input name="email" type="email" placeholder="seu@email.com" value="{{ old('email') }}" validate-input>
                    E-mail
                </x-input>

                @error('email')
                <x-warn>{{ $message }}</x-warn>
                @enderror
            </div>

            <div x-show="step === 3" x-cloak class="flex flex-col gap-3">
                <div class="text-left mb-6">
                    <h3 class="text-2xl font-bold text-gray-900">Adicione seu telefone</h3>
                    <p class="text-sm text-gray-700">Usaremos seu número para verificações de segurança e para manter sua conta protegida.</p>
                </div>

                <x-input name="telefone" type="tel" placeholder="(00)00000-0000" value="{{ old('telefone') }}" validate-input>
                    Telefone
                </x-input>

                @error('telefone')
                    <x-warn>{{ $message }}</x-warn>
                @enderror
            </div>

            <div x-show="step === 4" x-cloak class="flex flex-col gap-3">
                <div class="text-left mb-6">
                    <h3 class="text-2xl font-bold text-gray-900">Para sua segurança</h3>
                    <p class="text-sm text-gray-700">Esses dados são essenciais para a segurança do seu perfil e para validar suas candidaturas futuras.</p>
                </div>

                <x-input type="text" name="datanasc" datepicker datepicker-format="dd/mm/yyyy" placeholder="00/00/0000" value="{{ old('datanasc') }}"  validate-input>
                Data de Nascimento
                <x-slot:icon>
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4Z"/><path d="M0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                        </svg>
                    </x-slot:icon>
                </x-input>

                @error('datanasc')
                    <x-warn>{{ $message }}</x-warn>
                @enderror

                <x-input name="cpf" type="text" placeholder="000.000.000-00" value="{{ old('cpf') }}" validate-input>
                    CPF
                </x-input>   

                @error('cpf')
                    <x-warn>{{ $message }}</x-warn>
                @enderror
            </div>

            <div x-show="step === 5" x-cloak class="flex flex-col gap-3">
            <div class="text-left mb-6">
                    <h3 class="text-2xl font-bold text-gray-900">Onde você busca oportunidades?</h3>
                    <p class="text-sm text-gray-700">Sua localização nos ajuda a mostrar as melhores vagas de emprego perto de você.</p>
                </div>

            
                <div class="space-y-4">
                    <div>
                        <label for="estado-button" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Estado</label>
                        <div class="relative">
                            <button 
                                id="estado-button"
                                type="button" 
                                @click="stateDropdownOpen = !stateDropdownOpen"
                                :disabled="isLoadingStates"
                                class="flex items-center justify-between p-3.5 w-full text-left bg-gray-50 border border-gray-300 rounded-lg sm:text-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500 active:ring-blue-500 active:border-blue-500 disabled:opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-sky-500 disabled:bg-gray-200 dark:disabled:bg-gray-800 dark:disabled:text-gray-500">
                                <span x-text="isLoadingStates ? 'Carregando...' : selectedState.nome"></span>
                                <svg class="w-2.5 h-2.5 ms-3 transition-transform" :class="{'rotate-180': stateDropdownOpen}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                                </svg>
                            </button>


                            <div data-dropdown-container x-show="stateDropdownOpen" @click.away="stateDropdownOpen = false" x-cloak class="absolute z-10 w-full mt-1 bg-white rounded-lg shadow dark:bg-gray-700">
                                <div class="p-2">
                                    <input type="text" x-model="stateSearch" placeholder="Buscar estado..." class="w-full p-2 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white focus:ring-sky-500 focus:border-sky-500" validate-input>
                                </div>
                                <ul class="h-48 px-3 pb-3 overflow-y-auto text-sm text-gray-700 dark:text-gray-200">
                                    <template x-for="state in states.filter(s => s.nome.toLowerCase().includes(stateSearch.toLowerCase()))" :key="state.id">
                                        <li>
                                            <button type="button" @click="selectedState = state; stateDropdownOpen = false; fetchCities()" class="w-full text-left rounded-lg p-2 hover:bg-gray-100 dark:hover:bg-gray-600">
                                                <span x-text="state.nome"></span>
                                            </button>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="cidade-button" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Cidade</label>
                        <div class="relative">
                            <button 
                                id="cidade-button"
                                type="button" 
                                @click="cityDropdownOpen = !cityDropdownOpen" 
                                :disabled="!selectedState.sigla || isLoadingCities"
                                class="flex items-center justify-between p-2.5 w-full text-left bg-gray-50 border border-gray-300 rounded-lg sm:text-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500 active:ring-blue-500 active:border-blue-500 disabled:opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-sky-500 disabled:bg-gray-200 dark:disabled:bg-gray-800 dark:disabled:text-gray-500">
                                <span x-text="selectedCity || (isLoadingCities ? 'Carregando...' : 'Selecione uma Cidade')"></span>
                                <svg class="w-2.5 h-2.5 ms-3 transition-transform" :class="{'rotate-180': cityDropdownOpen}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                                </svg>
                            </button>

                            <div data-dropdown-container x-show="cityDropdownOpen" @click.away="cityDropdownOpen = false" x-cloak class="absolute z-10 w-full mt-1 bg-white rounded-lg shadow dark:bg-gray-700">
                                <div class="p-2">
                                    <input type="text" x-model="citySearch" placeholder="Buscar cidade..." class="w-full p-2 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white focus:ring-sky-500 focus:border-sky-500" validate-input>
                                </div>
                                <ul class="h-48 px-3 pb-3 overflow-y-auto text-sm text-gray-700 dark:text-gray-200">
                                    <template x-for="city in cities.filter(c => c.nome.toLowerCase().includes(citySearch.toLowerCase()))" :key="city.id">
                                        <li>
                                            <button type="button" @click="selectedCity = city.nome; cityDropdownOpen = false" class="w-full text-left rounded-lg p-2 hover:bg-gray-100 dark:hover:bg-gray-600">
                                                <span x-text="city.nome"></span>
                                            </button>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <input type="hidden" id="estado" name="estado" :value="selectedState.sigla">
                    <input type="hidden" id="cidade" name="cidade" :value="selectedCity">
                </div>
            </div> 
           
            <div x-show="step === 6" x-cloak class="flex flex-col gap-3">
            <div class="text-left mb-6">
                    <h3 class="text-2xl font-bold text-gray-900">Crie sua senha de acesso</h3>
                    <p class="text-sm text-gray-700">Escolha uma senha forte com letras, números e símbolos. Esta será a chave para proteger sua conta.</p>
                </div>                   
            
                <x-input name="password" type="password" placeholder="*******" validate-input>
                Senha
                </x-input>

                @error('password')
                    <x-warn>{{ $message }}</x-warn>
                @enderror

                <x-input name="password_confirmation" type="password" placeholder="*******" validate-input>
                Confirme sua senha
                </x-input>

            </div>
            
            <div class="navigation-area mx-auto fixed bottom-0 left-0 right-0 p-5 sm:w-[25rem]">
                <div x-show="step === 1">
                    <x-btn-primary type="button" @click="step = step + 1" validate-btn>Continuar</x-btn-primary>
                </div>

                <div x-show="[2, 3, 4, 5].includes(step)">
                    <x-btn-outline type="button" @click="step = step - 1" validate-btn>Voltar</x-btn-outline>
                    <x-btn-primary type="button" @click="step = step + 1" validate-btn>Continuar</x-btn-primary>
                </div>

                <div x-show="step === 6">
                    <x-btn-outline type="button" @click="step = step - 1" validate-btn>Voltar</x-btn-outline>
                    <x-btn-primary type="submit" validate-btn>Criar conta</x-btn-primary>
                </div>
            </div>
        </form>
      </div>
    </div>
    </div>
</body>
</html>