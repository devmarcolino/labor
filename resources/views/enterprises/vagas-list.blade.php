<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Labor for workers</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <link rel="shortcut icon" href="../img/lb-blue.svg" type="image/x-icon">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        [x-cloak] { display: none !important; }
        body.modal-open {
            overflow: hidden !important;
            touch-action: none;
        }
    </style>
</head>
<x-flash-manager />

<body class="bg-gray-50 dark:bg-gray-900 min-h-screen" x-data="{ modalOpen: false }" x-init="
    $watch('modalOpen', value => {
        if(value) document.body.classList.add('modal-open');
        else document.body.classList.remove('modal-open');
    });
" x-data="{ 
          modalOpen: false, 
          openVagaModal: false, 
          openDelete: false, 
          openCandidatosModal: false, // <--- NOVO
          vagaId: null,
          currentVagaId: null,
          activeModal: null // <--- NOVO (Para saber de qual vaga abrir os candidatos)
      }" @open-delete-modal.window="openDelete = true; vagaId = $event.detail.id"
     @open-candidates-modal.window="activeModal = $event.detail.id; console.log('Abriu vaga:', $event.detail.id)">
<div class="container mx-auto px-5 py-5 sm:py-9 max-w-md">
    <div class="flex items-center justify-between w-full max-w-2xl gap-2 mb-4">
        <x-btn-back/>
        <h1 class="text-xl font-bold text-gray-900 dark:text-white">Minhas <span class="text-sky-500">vagas</span></h1>
    </div>

    <!-- Controle do modal de criar vaga -->
    <div x-data="{ openVagaModal: false }" class="pb-2">
        <x-btn-primary @click="openVagaModal = true">
            <svg class="text-white" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
            <path d='M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0m-5.5 0H12m0 0H8.5m3.5 0V8.5m0 3.5v3.5'/>
            </svg>
            Criar nova vaga
            
        </x-btn-primary>

        <!-- Partial do modal de criar vaga -->
        <div>
            @include('partials.vaga-modal-create', ['skills' => $skills ?? []])
        </div>
    </div>

    <!-- Controle dos modais de deletar -->
    <div class="mt-2" x-data="{ openDelete: false, vagaId: null }" x-init="$watch('openDelete', value => { $root.modalOpen = value })">
        @foreach($vagas as $vaga)
        <div class="bg-white dark:bg-gray-800 rounded-[40px] shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden mb-8 transition-all hover:shadow-md relative group">
    
    <div class="px-5 pt-5 pb-3 flex justify-between items-start">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-full bg-gray-50 border border-gray-100 flex items-center justify-center overflow-hidden flex-shrink-0">
                @if(!empty($vaga->empresa->fotoEmpresa))
                    <img src="{{ asset('storage/' . $vaga->empresa->fotoEmpresa) }}" class="w-full h-full object-cover">
                @else
                    <span class="text-sm font-bold text-gray-400">
                        {{ substr($vaga->empresa->nome_empresa, 0, 2) }}
                    </span>
                @endif
            </div>
            
            <div class="flex flex-col">
                <h3 class="font-bold text-gray-900 dark:text-white text-lg leading-tight">
                    {{ $vaga->empresa->nome_empresa }}
                </h3>
                <span class="text-xs text-gray-400 font-medium">
                    {{ $vaga->empresa->ramo ?? 'Restaurante' }} • {{ $vaga->created_at->diffForHumans() }}
                </span>
            </div>
        </div>

        <div class="relative" x-data="{ openMenu: false }">
            <button @click="openMenu = !openMenu" class="p-2 -mr-2 rounded-full text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 10c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0-6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 12c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>
            </button>

            <div x-show="openMenu" @click.away="openMenu = false" x-cloak 
                 x-transition.origin.top.right
                 class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 z-30 overflow-hidden py-1">
                
                {{-- LÓGICA APLICADA: Só mostra botão de concluir se status == 1 --}}
                @if($vaga->status == 1)
                    <form action="{{ route('enterprises.vagas.concluir', $vaga->id) }}" method="POST" class="block w-full">
                        @csrf
                        @method('PATCH')
                        
                        <button type="submit" class="w-full text-left px-4 py-3 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 flex items-center gap-2 transition-colors">
                            <svg class="w-4 h-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Concluir Vaga
                        </button>
                    </form>
                @else
                    {{-- Se já for 0, mostra aviso --}}
                    <div class="w-full text-left px-4 py-3 text-sm text-gray-400 flex items-center gap-2 cursor-not-allowed">
                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Vaga Concluída
                    </div>
                @endif
                
                <div class="h-px bg-gray-100 dark:bg-gray-700 my-1"></div>
                
                <button type="button"
                        @click="$dispatch('open-delete-modal', { id: {{ $vaga->id }} }); openMenu = false"
                        class="w-full text-left px-4 py-3 text-sm text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 flex items-center gap-2 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    Deletar Vaga
                </button>
            </div>
        </div>
    </div>

    <div class="w-full h-56 bg-gray-200 relative group-hover:brightness-[0.98] transition-all">
        
        {{-- LÓGICA APLICADA: Overlay de Concluída --}}
        @if($vaga->status == 0)
            <div class="absolute inset-0 z-10 bg-gray-900/60 backdrop-blur-[1px] flex items-center justify-center">
                <div class="bg-green-600/90 backdrop-blur-md px-4 py-1.5 rounded-full flex items-center gap-2 shadow-lg border border-white/20">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    <span class="text-sm font-bold text-white tracking-wide">CONCLUÍDA</span>
                </div>
            </div>
        @endif

        @if(!empty($vaga->imgVaga))
            <img src="{{ asset('storage/' . $vaga->imgVaga) }}" class="w-full h-full object-cover {{ $vaga->status == 0 ? 'grayscale' : '' }}">
        @else
            <div class="w-full h-full flex flex-col items-center justify-center text-gray-400 bg-gray-100 pattern-grid-lg">
                <svg class="w-12 h-12 opacity-30 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <span class="text-xs opacity-50 font-medium uppercase tracking-wider">Sem capa</span>
            </div>
        @endif
        
        @if($vaga->status == 1)
            <div class="flex items-center absolute bottom-4 left-4 px-4 py-1.5 gap-2 bg-gray-900/60 backdrop-blur-md border border-white/10 rounded-full shadow-lg">
            <svg class="w-4 h-4 text-sky-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
            <span class="text-sm font-bold text-white">R$ {{ number_format($vaga->valor_vaga, 2, ',', '.') }}</span>
            </div>
    
        @endif
    </div>

    <div class="p-5 {{ $vaga->status == 0 ? 'opacity-60' : '' }}">
        <div class="mb-4">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white leading-tight mb-1">
                {{ \App\Models\Skill::find($vaga->funcVaga)?->nomeHabilidade ?? $vaga->funcVaga }}
            </h2>
            
            <div class="flex items-center gap-2 text-gray-500 text-sm mb-3">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>{{ $vaga->horario ?? 'Horário a combinar' }}</span>
            </div>

            <p class="text-sm text-gray-500 leading-relaxed line-clamp-2">
                {{ $vaga->descVaga }}
            </p>
        </div>

        <div class="flex items-center gap-6 pb-5 border-b border-gray-100 dark:border-gray-700">
            <div class="flex items-center gap-1.5 text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                <span class="text-xs font-semibold">{{ $vaga->visualizacoes ?? 0 }} visualizações</span>
            </div>
            <div class="flex items-center gap-1.5 text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                <span class="text-xs font-semibold">{{ $vaga->candidaturas->count() }} interessados</span>
            </div>
        </div>

        <div class="pt-4">
    <button @click="$dispatch('open-candidates-modal', { id: {{ $vaga->id }} })" 
            class="w-full bg-gray-50 hover:bg-gray-100 dark:bg-gray-700/50 dark:hover:bg-gray-700 rounded-full p-3 flex items-center justify-between group transition-colors">
        
        <div class="flex items-center gap-3">
            
            {{-- CENÁRIO 1: VAGA CONCLUÍDA (STATUS 0) --}}
            @if($vaga->status == 0)
                <div class="w-10 h-10 rounded-full bg-green-100 border border-green-200 flex items-center justify-center text-green-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <div class="flex flex-col text-left">
                    <span class="text-sm font-bold text-gray-700 dark:text-white">Processo Finalizado</span>
                    <span class="text-xs text-gray-400">Ver histórico</span>
                </div>

            {{-- CENÁRIO 2: VAGA ATIVA COM CANDIDATOS --}}
            @elseif($vaga->candidaturas->count() > 0)
                <div class="flex -space-x-3">
                    @foreach($vaga->candidaturas->take(3) as $candidatura)
                        <img src="{{ $candidatura->user->fotoUser ? asset('storage/'.$candidatura->user->fotoUser) : asset('img/default-avatar.png') }}" 
                             class="w-10 h-10 rounded-full border-2 border-white dark:border-gray-800 object-cover">
                    @endforeach
                    @if($vaga->candidaturas->count() > 3)
                        <div class="w-10 h-10 rounded-full border-2 border-white bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600">
                            +{{ $vaga->candidaturas->count() - 3 }}
                        </div>
                    @endif
                </div>
                <span class="text-sm font-bold text-gray-700 dark:text-white">Ver Candidatos</span>

            {{-- CENÁRIO 3: VAGA ATIVA SEM CANDIDATOS --}}
            @else
                <div class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <span class="text-sm font-medium text-gray-500">Aguardando candidatos...</span>
            @endif
        </div>

        <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-600 flex items-center justify-center text-gray-400 group-hover:text-sky-600 transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </div>
    </button>
</div>
    </div>
</div>
        @include('partials.vaga-candidatos-modal', ['vaga' => $vaga])

        @endforeach
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.vaga-img').forEach(function(img) {
        img.addEventListener('load', function() {
            try {
                const canvas = document.createElement('canvas');
                canvas.width = img.naturalWidth;
                canvas.height = img.naturalHeight;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0);
                const data = ctx.getImageData(0, 0, canvas.width, canvas.height).data;
                let r = 0, g = 0, b = 0, count = 0;
                for(let i = 0; i < data.length; i += 4*100) { // Amostra pixels para performance
                    r += data[i];
                    g += data[i+1];
                    b += data[i+2];
                    count++;
                }
                r = Math.round(r/count);
                g = Math.round(g/count);
                b = Math.round(b/count);
                const bgColor = `rgb(${r},${g},${b})`;
                img.parentElement.style.background = bgColor;
            } catch(e) {}
        });
    });
});

function confirmarExclusao(id) {
        // 1. Monta a URL correta
        const urlBase = "{{ url('enterprises/vagas/delete') }}";
        const urlFinal = `${urlBase}/${id}`;
        
        // 2. Injeta no formulário
        document.getElementById('formDeleteVaga').action = urlFinal;
        
        // 3. Abre a modal usando o Alpine do corpo da página
        // (Acessa o escopo do x-data no body)
        document.querySelector('[x-data]').__x.$data.openDelete = true;
    }
</script>

   <div x-data="{ show: false, deleteUrl: '' }"
     @open-delete-modal.window="show = true; deleteUrl = '/enterprises/vagas/delete/' + $event.detail.id"
     x-show="show" 
     x-cloak
     class="fixed inset-0 z-[99] flex items-center justify-center bg-gray-900/90 backdrop-blur-sm p-4"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">

    <div class="relative w-full max-w-sm bg-white dark:bg-gray-800 rounded-[40px] p-6 shadow-2xl flex flex-col items-center text-center"
         @click.away="show = false">
        
        <div class="w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mb-4 text-red-500 animate-pulse">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
        </div>

        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Excluir Vaga?</h3>
        <p class="text-gray-500 dark:text-gray-400 text-sm mb-6">
            Essa ação apagará todos os dados da vaga permanentemente.
        </p>

        <form :action="deleteUrl" method="POST" class="w-full flex flex-col gap-2">
            @csrf
            @method('DELETE')
            
            <x-btn-red type="submit">
                Sim, excluir vaga
            </x-btn-red>

            <x-btn-outline-account type="button" @click="show = false">
                Cancelar
            </x-btn-outline-account>
        </form>
    </div>
</div>
</div>
</body>
</html>
