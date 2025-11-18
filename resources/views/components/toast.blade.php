@props(['type' => 'info', 'msg' => '', 'title' => ''])

@php
    // Configuração de Estilos e Ícones baseados no Tipo
    $styles = [
        'success' => [
            'bg' => 'bg-white dark:bg-gray-800 border-l-4 border-green-500',
            'text' => 'text-gray-800 dark:text-white',
            'icon_bg' => 'bg-green-100 text-green-600 dark:bg-green-900 dark:text-green-300',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>' // Check
        ],
        'danger' => [
            'bg' => 'bg-white dark:bg-gray-800 border-l-4 border-red-500',
            'text' => 'text-gray-800 dark:text-white',
            'icon_bg' => 'bg-red-100 text-red-600 dark:bg-red-900 dark:text-red-300',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>' // X
        ],
        'warning' => [
            'bg' => 'bg-white dark:bg-gray-800 border-l-4 border-yellow-500',
            'text' => 'text-gray-800 dark:text-white',
            'icon_bg' => 'bg-yellow-100 text-yellow-600 dark:bg-yellow-900 dark:text-yellow-300',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>' // !
        ],
        'info' => [
            'bg' => 'bg-white dark:bg-gray-800 border-l-4 border-blue-500',
            'text' => 'text-gray-800 dark:text-white',
            'icon_bg' => 'bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-300',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>' // i
        ],
    ];

    $style = $styles[$type] ?? $styles['info'];
@endphp

<div x-data="{ show: true }"
     x-show="show"
     x-init="setTimeout(() => show = false, 7000)" 
     x-transition:enter="transform ease-out duration-300 transition"
     x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
     x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
     x-transition:leave="transition ease-in duration-100"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="pointer-events-auto w-full max-w-sm overflow-hidden rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 {{ $style['bg'] }} mb-3"
     style="display: none;"> {{-- display:none evita piscar antes do Alpine carregar --}}
    
    <div class="p-4">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <div class="flex h-8 w-8 items-center justify-center rounded-full {{ $style['icon_bg'] }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        {!! $style['icon'] !!}
                    </svg>
                </div>
            </div>
            
            <div class="ml-3 w-0 flex-1 pt-0.5">
                @if($title)
                    <p class="text-sm font-medium {{ $style['text'] }}">{{ $title }}</p>
                @endif
                
                <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    @if(!empty($msg))
                        {{ $msg }}
                    @else
                        {{ $slot }}
                    @endif
                </div>
            </div>

            <div class="ml-4 flex flex-shrink-0">
                <button @click="show = false" class="inline-flex rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    <span class="sr-only">Fechar</span>
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>