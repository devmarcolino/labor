@props(['name'])

<div>
    {{-- Label (O $slot aqui é o texto do Label) --}}
    <label for="{{ $name }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
        {{ $slot }}
    </label>

    <div class="relative">
        {{-- 
            O Input em si.
            Adicionei 'pr-10' (padding-right) para o texto não ficar embaixo do ícone.
        --}}
        <input id="{{ $name }}" name="{{ $name }}"
            {{ $attributes->merge(['class'=>'bg-gray-50/85 backdrop-blur-md border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 pr-10 dark:bg-gray-700/85 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 placeholder:text-neutral-400 disabled:opacity-50 disabled:bg-gray-200 dark:disabled:bg-gray-800 dark:disabled:text-gray-500']) }}
        />

        {{-- ÁREA DOS ÍCONES (Spinner e Check) --}}
        
        {{-- 1. SPINNER (Aparece se isChecking for true) --}}
        <div x-show="isChecking['{{ $name }}']" 
             style="display: none;"
             class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
             
             {{-- Seu componente de spinner --}}
             <x-spinner /> 
        </div>

        {{-- 2. CHECK AZUL (Aparece se: NÃO verificando E SEM erros E TEM valor) --}}
        <div x-show="!isChecking['{{ $name }}'] && !errors['{{ $name }}'] && fields['{{ $name }}']" 
             style="display: none;"
             class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none transition-opacity duration-300">
            
             <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
        </div>

        {{-- 3. ÍCONE DE ERRO (Opcional: Exclamação vermelha se tiver erro) --}}
        <div x-show="!isChecking['{{ $name }}'] && errors['{{ $name }}']" 
             style="display: none;"
             class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-red-500">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>

    </div>
</div>