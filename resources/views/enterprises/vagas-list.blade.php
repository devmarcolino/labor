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
        <h1 class="text-xl font-bold text-gray-900">Minhas Vagas</h1>
    </div>

    <!-- Controle do modal de criar vaga -->
    <div x-data="{ openVagaModal: false }" x-init="$watch('openVagaModal', value => { $root.modalOpen = value })">
        <button @click="openVagaModal = true" class="w-full bg-sky-600 text-white font-semibold py-3 rounded-full mb-5 flex items-center justify-center gap-2 shadow">
            <span>+ Criar nova vaga</span>
        </button>

        <!-- Partial do modal de criar vaga -->
        <div>
            @include('partials.vaga-modal-create', ['skills' => $skills ?? []])
        </div>
    </div>

    <!-- Controle dos modais de deletar -->
    <div x-data="{ openDelete: false, vagaId: null }" x-init="$watch('openDelete', value => { $root.modalOpen = value })">
        @foreach($vagas as $vaga)
        <div class="bg-gray-800 rounded-2xl shadow-md p-4 mb-6 dark:bg-gray-800">
            <div class="flex items-center gap-2 mb-2">
                @if(!empty($vaga->empresa->fotoEmpresa))
                    <img src="{{ asset('storage/' . $vaga->empresa->fotoEmpresa) }}" alt="Logo empresa" class="w-8 h-8 rounded-full object-cover">
                @else
                    <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center text-gray-500 text-xs">?</div>
                @endif
                <div>
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $vaga->empresa->nome_empresa ?? '' }}</span>
                    <div class="text-xs text-gray-400 dark:text-gray-300">{{ $vaga->empresa->cidade ?? '' }}</div>
                </div>
            </div>

            <div class="rounded-xl overflow-hidden mb-3">
                @if(!empty($vaga->imgVaga))
                    <div class="w-full h-32 flex items-center justify-center rounded-xl overflow-hidden vaga-img-bg" style="background: #fff;">
                        <img src="{{ asset('storage/' . $vaga->imgVaga) }}" alt="Foto da vaga" class="h-full object-contain vaga-img" style="max-width:100%; max-height:100%;">
                    </div>
                @else
                    <div class="w-full h-32 bg-gray-100 flex items-center justify-center text-gray-400 text-sm">Sem imagem da vaga</div>
                @endif
            </div>

            <div class="font-bold text-md text-gray-900 mb-1">
                <span class="font-bold text-md text-gray-900 dark:text-white mb-1">{{ $vaga->funcVaga ?? '' }} ({{ $vaga->horario ?? 'horario da vaga' }})</span>
            </div>

            <ul class="text-sm text-gray-500 mb-2 list-disc pl-5">
                <li class="text-gray-500 dark:text-gray-300">{{ $vaga->descVaga ?? '' }}</li>
            </ul>

            <div class="flex gap-6 mb-2">
                <div class="flex items-center gap-1 text-gray-400 dark:text-gray-200">
                    <img src="/img/eye.svg" alt="Visualizações" class="w-4 h-4">
                    <span class="text-xs">{{ $vaga->visualizacoes ?? 0 }} visualizações</span>
                </div>
                @if(Auth::guard('empresa')->id() !== $vaga->idEmpresa)
                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    fetch("{{ route('vagas.visualizar') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ vaga_id: {{ $vaga->id }} })
                    });
                });
                </script>
                @endif
                <div class="flex items-center gap-1 text-gray-400">
                    <img src="/img/heart-handshake.svg" alt="Gostaram" class="w-4 h-4">
                    <span class="text-xs">{{ $vaga->gostaram ?? 0 }} gostaram</span>
                </div>
            </div>

            <div class="flex items-center gap-2 mb-3">
                @if(isset($vaga->candidatos) && count($vaga->candidatos) > 0 && !empty($vaga->candidatos[0]->foto))
                    <img src="{{ asset('storage/' . $vaga->candidatos[0]->foto) }}" alt="Candidato" class="w-8 h-8 rounded-full object-cover">
                @else
                    <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center text-gray-500 text-xs">?</div>
                @endif
                <div>
                    <span class="font-semibold text-gray-900 dark:text-white">Candidatos</span>
                    <div class="text-xs text-gray-400 dark:text-gray-300">Ver informações</div>
                </div>
                <span class="ml-auto text-gray-400 dark:text-gray-200 text-xs">{{ isset($vaga->candidatos) ? count($vaga->candidatos) : 0 }}</span>
            </div>

            <div class="flex gap-2">
                <button class="flex-1 bg-sky-600 text-white font-semibold py-2 rounded-full flex items-center justify-center gap-2">
                    <img src="/img/heart-handshake.svg" alt="Concluir" class="w-5 h-5">
                    Concluir vaga
                </button>

                <button
                    class="flex-1 bg-red-500 text-white font-semibold py-2 rounded-full flex items-center justify-center gap-2"
                    @click="openDelete = true; vagaId = {{ $vaga->id }};">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M6 6L18 18M6 18L18 6"/>
                    </svg>
                    Deletar vaga
                </button>
            </div>

            <!-- Modal de confirmação -->
            <div
                x-show="openDelete"
                x-cloak
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-70 backdrop-blur-sm p-2"
            >
                <div class="relative w-full max-w-sm rounded-3xl bg-white pt-6 pb-4 px-4 shadow-labor dark:bg-gray-800 flex flex-col max-h-[95vh] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
                    <button 
                        @click="openDelete = false"
                        class="absolute top-2 right-2 text-gray-500 hover:text-gray-800 text-2xl leading-none"
                        aria-label="Fechar modal"
                    >&times;</button>
                    <div class="flex flex-col items-center mb-4">
                        <svg class="w-12 h-12 text-red-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Confirmar exclusão</h2>
                        <p class="text-gray-500 mb-2 text-center text-sm">Tem certeza que deseja deletar esta vaga? Esta ação não poderá ser desfeita.</p>
                    </div>
                    <div class="flex flex-col gap-2 w-full mt-2">
                        <button @click="openDelete = false" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-3 px-6 rounded-full w-full">Cancelar</button>
                        <form :action="'/enterprises/vagas/delete/' + vagaId" method="POST" class="w-full" x-show="vagaId != null">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-6 rounded-full shadow-labor transition-all duration-200">Confirmar exclusão</button>
                        </form>
                    </div>
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
