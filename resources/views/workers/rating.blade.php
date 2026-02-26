<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Minhas Avaliações</title>
        <link rel="manifest" href="/manifest.webmanifest">
        <meta name="theme-color" content="#0ea5e9">
        <meta name="mobile-web-app-capable" content="yes">
        <link rel="apple-touch-icon" href="/img/auth-worker.png">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="shortcut icon" href="../img/lb-blue.svg" type="image/x-icon">
</head>

<x-flash-manager />

<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-500 min-h-screen flex flex-col">
    
    <x-loading/>

    <!-- HEADER -->
    <header class="flex justify-center w-full mx-auto pt-4 mb-6">
        <div class="flex items-center justify-between w-full max-w-2xl px-5 relative">
            <a href="{{ route('workers.account') }}" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-800 transition-colors">
                <svg class="w-6 h-6 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            
            <h1 class="text-xl font-bold text-center dark:text-white">Minhas avaliações</h1>
        </div>
    </header>

    <main class="max-w-md w-full mx-auto px-5 pb-10 space-y-6">

        @forelse($avaliacoes as $avaliacao)
            <!-- CARD DE AVALIAÇÃO (Estilo Vagas List) -->
            <div class="bg-white dark:bg-gray-800 rounded-[40px] p-6 shadow-labor border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-all relative group">
                
                <!-- Cabeçalho: Foto + Nome + Data -->
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <!-- Foto da Empresa -->
                        <div class="w-12 h-12 rounded-full border border-gray-100 dark:border-gray-600 overflow-hidden flex-shrink-0">
                            @if($avaliacao->avaliador && $avaliacao->avaliador->fotoEmpresa)
                                <img src="{{ asset('storage/' . $avaliacao->avaliador->fotoEmpresa) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-400 font-bold">
                                    {{ substr($avaliacao->avaliador->nome_empresa ?? 'Empresa', 0, 2) }}
                                </div>
                            @endif
                        </div>
                        
                        <div class="flex flex-col">
                            <h3 class="font-bold text-gray-900 dark:text-white text-lg leading-tight">
                                {{ $avaliacao->avaliador->nome_empresa ?? 'Empresa' }}
                            </h3>
                            <span class="text-xs text-gray-400 font-medium">
                                {{ $avaliacao->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>

                    <!-- Nota Numérica (Badge) -->
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 text-yellow-600 dark:text-yellow-400 px-3 py-1 rounded-full text-sm font-bold flex items-center gap-1">
                        <span>{{ $avaliacao->nota }}.0</span>
                        <svg class="w-3 h-3 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                    </div>
                </div>

                <!-- Estrelas -->
                <div class="flex gap-1 mb-3">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= $avaliacao->nota)
                            <!-- Cheia -->
                            <svg class="w-6 h-6 text-yellow-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        @else
                            <!-- Vazia -->
                            <svg class="w-6 h-6 text-gray-200 dark:text-gray-600" fill="currentColor" viewBox="0 0 24 24"><path d="M22 9.24l-7.19-.62L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21 12 17.27 18.18 21l-1.63-7.03L22 9.24zM12 15.4l-3.76 2.27 1-4.28-3.32-2.88 4.38-.38L12 6.1l1.71 4.01 4.38.38-3.32 2.88 1 4.28L12 15.4z"/></svg>
                        @endif
                    @endfor
                </div>

                <!-- Comentário -->
                @if($avaliacao->comentario)
                    <div class="bg-gray-50 dark:bg-gray-700/30 p-4 rounded-2xl">
                        <p class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed italic">
                            "{{ $avaliacao->comentario }}"
                        </p>
                    </div>
                @else
                    <p class="text-gray-400 text-sm italic ml-1">Sem comentário escrito.</p>
                @endif

            </div>
        @empty
            <!-- EMPTY STATE -->
            <div class="flex flex-col items-center justify-center py-20 text-center">
                <div class="w-24 h-24 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Ainda sem avaliações</h3>
                <p class="text-gray-500 dark:text-gray-400 max-w-xs mx-auto mt-2">
                    Complete suas escalas com excelência para receber avaliações das empresas!
                </p>
            </div>
        @endforelse

    </main>
</body>
</html>