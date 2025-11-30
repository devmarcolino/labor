<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Vagas</title>

    <style>
        [x-cloak] { display: none !important; }
        body.modal-open {
            overflow: hidden !important;
            touch-action: none;
        }
    </style>

    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>
<body class="bg-gray-50 dark:bg-gray-900 min-h-screen" x-data="{ modalOpen: false }" x-init="
    $watch('modalOpen', value => {
        if(value) document.body.classList.add('modal-open');
        else document.body.classList.remove('modal-open');
    });
">
<div class="container mx-auto px-2 py-4 max-w-md">
    <div class="flex items-center gap-2 mb-4">
        <x-btn-back />
        <h1 class="text-xl font-bold text-gray-900 dark:text-white">Minhas Vagas</h1>
    </div>

    <!-- Controle do modal de criar vaga -->
    <div x-data="{ openVagaModal: false }">
        <x-btn-primary @click="openVagaModal = true">
            Criar nova vaga
        </x-btn-primary>

        <!-- Partial do modal de criar vaga -->
        <div>
            @include('partials.vaga-modal-create', ['skills' => $skills ?? []])
        </div>
    </div>

    <!-- Controle dos modais de deletar -->
    <div x-data="{ openDelete: false, vagaId: null }" x-init="$watch('openDelete', value => { $root.modalOpen = value })">
        @foreach($vagas as $vaga)
        <div class="bg-white dark:bg-gray-800 rounded-[30px] shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden mb-6 transition-all hover:shadow-md">
    
    <div class="p-4 flex items-center gap-3">
        <div class="w-12 h-12 rounded-full bg-gray-100 border border-gray-200 flex items-center justify-center overflow-hidden flex-shrink-0">
            @if(!empty($vaga->empresa->fotoEmpresa))
                <img src="{{ asset('storage/' . $vaga->empresa->fotoEmpresa) }}" class="w-full h-full object-cover">
            @else
                <span class="text-sm font-bold text-gray-500">
                    {{ substr($vaga->empresa->nome_empresa, 0, 2) }}
                </span>
            @endif
        </div>
        
        <div class="flex flex-col">
            <h3 class="font-bold text-gray-900 dark:text-white text-lg leading-tight">
                {{ $vaga->empresa->nome_empresa }}
            </h3>
            <span class="text-xs text-gray-400 font-medium">
                {{ $vaga->empresa->ramo ?? 'Restaurante' }} </span>
        </div>
    </div>

    <div class="w-full h-48 bg-gray-200 relative">
        @if(!empty($vaga->imgVaga))
            <img src="{{ asset('storage/' . $vaga->imgVaga) }}" class="w-full h-full object-cover">
        @else
            <div class="w-full h-full flex items-center justify-center text-gray-400 bg-gray-100">
                <svg class="w-10 h-10 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
        @endif
        
        <div class="absolute bottom-3 right-3 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-bold text-green-700 shadow-sm">
            R$ {{ number_format($vaga->valor_vaga, 2, ',', '.') }}
        </div>
    </div>

    <div class="p-5">
        <div class="mb-3">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                {{ \App\Models\Skill::find($vaga->funcVaga)?->nome ?? $vaga->funcVaga }}
                <span class="text-gray-400 font-normal text-base ml-1">
                    ({{ $vaga->horario ?? 'Horário a combinar' }})
                </span>
            </h2>
            <p class="text-sm text-gray-500 mt-1 line-clamp-2">
                {{ $vaga->descVaga }}
            </p>
        </div>

        <div class="flex items-center gap-4 text-xs text-gray-500 font-medium mb-4">
            <div class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                {{ $vaga->visualizacoes ?? 0 }} visualizações
            </div>
            <div class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                {{ $vaga->candidaturas->count() }} candidaturas
            </div>
        </div>

        <div class="bg-gray-50 dark:bg-gray-700/30 rounded-2xl p-3 flex items-center justify-between mb-5 cursor-pointer hover:bg-gray-100 transition-colors"
             @click="openCandidatosModal = true; currentVagaId = {{ $vaga->id }}">
            
            <div class="flex items-center gap-3">
                @if($vaga->candidaturas->count() > 0)
                    <div class="flex -space-x-3">
                        @foreach($vaga->candidaturas->take(3) as $candidatura)
                            <img class="w-10 h-10 rounded-full border-2 border-white object-cover" 
                                 src="{{ $candidatura->user->fotoUser ? asset('storage/'.$candidatura->user->fotoUser) : asset('img/default-avatar.png') }}" 
                                 alt="Candidato">
                        @endforeach
                        @if($vaga->candidaturas->count() > 3)
                            <div class="w-10 h-10 rounded-full border-2 border-white bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600">
                                +{{ $vaga->candidaturas->count() - 3 }}
                            </div>
                        @endif
                    </div>
                @else
                    <div class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                @endif

                <div class="flex flex-col">
                    <span class="font-bold text-gray-900 dark:text-white text-sm">Candidatos</span>
                    <span class="text-xs text-gray-400">Ver informações</span>
                </div>
            </div>

            <span class="text-gray-400 font-medium text-sm">{{ $vaga->candidaturas->count() }}</span>
        </div>

        <div class="flex gap-3">
            <button class="flex-1 bg-sky-600 hover:bg-sky-700 text-white font-bold py-3 rounded-full flex items-center justify-center gap-2 shadow-sm transition-transform active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Concluir vaga
            </button>

            <button class="flex-1 bg-red-500 hover:bg-red-600 text-white font-bold py-3 rounded-full flex items-center justify-center gap-2 shadow-sm transition-transform active:scale-95"
                    @click="openDelete = true; vagaId = {{ $vaga->id }};">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                Deletar vaga
            </button>
        </div>

    </div>
</div>
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
</script>
</body>
</html>
