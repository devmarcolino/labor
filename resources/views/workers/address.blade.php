<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <title>Labor for workers</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/imask"></script>
    <link rel="shortcut icon" href="../img/lb-blue.svg" type="image/x-icon">
</head>
<x-flash-manager />
<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-500 h-screen flex flex-col"
      x-data="{ openModal: false }">
    
    <header class="flex justify-center w-full mx-auto pt-4 mb-2">
        <div class="flex items-center justify-between w-full max-w-2xl px-5 relative">
            <a href="{{ route('workers.account') }}" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-800 transition-colors">
                <svg class="w-6 h-6 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            
            <h1 class="text-xl font-bold text-center dark:text-white">Meu endereço</h1>
        </div>
    </header>

    <div class="px-5 pt-6 max-w-2xl mx-auto w-full flex-1 flex flex-col gap-6">
        
        <div class="bg-white dark:bg-gray-800 rounded-[30px] px-6 pt-6 pb-4 shadow-labor border border-gray-100 dark:border-gray-700">
            <div class="flex items-start gap-4">
                <div class="p-3 bg-sky-100 dark:bg-sky-900/30 rounded-full text-sky-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Localização Atual</h3>
                    
                    @if($user->endereco?->cep)
                        <p class="text-gray-600 dark:text-gray-300 text-sm">
                            {{ $user->endereco?->rua }}, {{ $user->endereco?->numero }}
                        </p>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">
                            {{ $user->endereco?->bairro }} - {{ $user->endereco?->cidade }}/{{ $user->endereco?->uf }}
                        </p>
                        <p class="text-gray-400 text-xs mt-1">{{ preg_replace("/(\d{5})(\d{3})/", "$1-$2", $user->endereco?->cep) }}</p>
                    @else
                        <p class="text-gray-400 text-sm">Nenhum endereço cadastrado.</p>
                    @endif
                </div>
            </div>

            <div class="mt-8">
                <x-btn-primary @click="openModal = true">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Editar Endereço
                </x-btn-primary>
            </div>
        </div>
    </div>

    <<div x-show="openModal" 
    x-cloak  
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="relative z-50">
        @include('partials.address-edit-modal', [
            'addressData' => $user->endereco ? $user->endereco->toArray() : [],
            'actionUrl' => route('workers.update.address') // <--- APONTA PARA A ROTA DA EMPRESA
         ])
    </div>

</body>
</html>