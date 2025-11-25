<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vagas Curtidas</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-50 dark:bg-gray-900">
    <div class="container mx-auto py-4 px-2">
        <!-- Botão Voltar -->
        <div class="flex items-center mb-4">
            <button onclick="window.history.back()" class="inline-flex items-center px-4 py-2 rounded-full bg-gray-100 text-gray-700 shadow hover:bg-gray-200 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                Voltar
            </button>
            <h1 class="text-xl font-bold ml-4">Vagas Curtidas</h1>
        </div>
        <div class="grid grid-cols-1 gap-4">
            @forelse($vagasCurtidas as $curtida)
                @if($curtida->vaga)
                <div class="bg-white rounded-2xl shadow-labor border-btn flex flex-col gap-2 items-center pt-4 pb-3 px-3">
                    <img src="{{ asset('vagas_img/' . basename($curtida->vaga->imgVaga)) }}" alt="Imagem da vaga" class="w-full h-32 object-cover rounded-xl mb-2 border-btn" />
                    <h2 class="text-lg font-bold text-gray-900 mb-1 text-center">{{ $curtida->vaga->tipoVaga ?? 'Sem título' }}</h2>
                    <p class="mb-1 text-gray-600 text-center text-sm">{{ $curtida->vaga->descVaga ?? 'Sem descrição' }}</p>
                    <span class="text-xs text-gray-500">Curtida em: {{ $curtida->created_at->format('d/m/Y H:i') }}</span>
                    <a href="/vagas/{{ $curtida->vaga->id }}" class="block mt-2 text-blue-600 hover:underline font-semibold text-center text-sm">Ver detalhes</a>
                </div>
                @endif
            @empty
                <p class="col-span-1 text-center text-gray-500">Nenhuma vaga curtida ainda.</p>
            @endforelse
        </div>
    </div>
</body>
</html>
