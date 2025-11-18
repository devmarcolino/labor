<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Vagas</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>
<body class="bg-gray-50 min-h-screen">
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-xl font-bold text-gray-900">Minhas Vagas</h1>
    </div>
    <button onclick="window.location.href='{{ route('enterprises.vagas.create') }}'" class="w-full bg-sky-600 text-white font-semibold py-3 rounded-full mb-5 flex items-center justify-center gap-2 shadow">
        <span>+ Criar nova vaga</span>
    </button>
    <div x-data="{ openDelete: false, vagaId: null }">
        @foreach($vagas as $vaga)
        <div class="bg-white rounded-2xl shadow-md p-4 mb-6">
            <div class="flex items-center gap-2 mb-2">
                @if(!empty($vaga->empresa->fotoEmpresa))
                    <img src="{{ asset('storage/' . $vaga->empresa->fotoEmpresa) }}" alt="Logo empresa" class="w-8 h-8 rounded-full object-cover">
                @else
                    <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center text-gray-500 text-xs">?</div>
                @endif
                <div>
                    <span class="font-semibold text-gray-900">{{ $vaga->empresa->nome_empresa ?? '' }}</span>
                    <div class="text-xs text-gray-400">{{ $vaga->empresa->cidade ?? '' }}</div>
                </div>
            </div>
            <div class="rounded-xl overflow-hidden mb-3">
                @if(!empty($vaga->imgVaga))
                    <img src="{{ asset('storage/' . $vaga->imgVaga) }}" alt="Foto da vaga" class="w-full h-32 object-cover">
                @else
                    <div class="w-full h-32 bg-gray-100 flex items-center justify-center text-gray-400 text-sm">Sem imagem da vaga</div>
                @endif
            </div>
            <div class="font-bold text-md text-gray-900 mb-1">{{ $vaga->funcVaga ?? '' }} ({{ $vaga->horario ?? 'horario da vaga' }})</div>
            <ul class="text-sm text-gray-500 mb-2 list-disc pl-5">
                <li>{{ $vaga->descVaga ?? '' }}</li>
            </ul>
            <div class="flex gap-6 mb-2">
                <div class="flex items-center gap-1 text-gray-400">
                    <img src="/img/eye.svg" alt="Visualizações" class="w-4 h-4">
                    <span class="text-xs">{{ $vaga->visualizacoes ?? 0 }} visualizações</span>()
                </div>
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
                    <span class="font-semibold text-gray-900">Candidatos</span>
                    <div class="text-xs text-gray-400">Ver informações</div>
                </div>
                <span class="ml-auto text-gray-400 text-xs">{{ isset($vaga->candidatos) ? count($vaga->candidatos) : 0 }}</span>
            </div>
            <div class="flex gap-2">
                <button class="flex-1 bg-sky-600 text-white font-semibold py-2 rounded-full flex items-center justify-center gap-2">
                    <img src="/img/heart-handshake.svg" alt="Concluir" class="w-5 h-5">
                    Concluir vaga
                </button>
                <button class="flex-1 bg-red-500 text-white font-semibold py-2 rounded-full flex items-center justify-center gap-2" @click="openDelete = true; vagaId = {{ $vaga->id }};">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 6L18 18M6 18L18 6"/></svg>
                    Deletar vaga
                </button>
            </div>
            <!-- Modal de confirmação -->
            <div x-show="openDelete" x-cloak x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-90 backdrop-blur-sm p-4">
                <div class="relative w-full max-w-md rounded-[40px] bg-white pt-10 pb-6 px-6 shadow-2xl flex flex-col items-center">
                    <svg class="w-12 h-12 text-red-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    <h2 class="text-xl font-bold text-gray-900 mb-2">Confirmar exclusão</h2>
                    <p class="text-gray-500 mb-6 text-center">Tem certeza que deseja deletar esta vaga? Esta ação não poderá ser desfeita.</p>
                    <div class="flex gap-2 w-full">
                        <button @click="openDelete = false" class="flex-1 bg-gray-200 text-gray-700 font-semibold py-2 rounded-full">Cancelar</button>
                        <form action="{{ url('/enterprises/vagas/delete/' . $vaga->id) }}" method="POST" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full bg-red-500 text-white font-semibold py-2 rounded-full">Confirmar exclusão</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@include('partials.modal-delete-vaga')
<script>
    window.modalDeleteVaga = {
        open: false,
        vagaId: null
    };
</script>
</body>
</html>
