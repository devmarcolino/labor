<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Labor for enterprises</title>

    @vite('resources/css/app.css')
    @vite('resources/js/app.js')

    <link rel="shortcut icon" href="../img/lb-blue.svg" type="image/x-icon">
</head>

<x-flash-manager />

<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-500 h-screen flex flex-col">

    <x-loading/>

    <!-- HEADER -->
    <header class="flex justify-center w-full mx-auto pt-4 mb-2">
        <div class="flex items-center justify-between w-full max-w-2xl px-5 relative">

            <a href="{{ route('enterprises.dashboard') }}"
               class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-800 transition-colors">
                <svg class="w-6 h-6 text-gray-900 dark:text-white" fill="none"
                     stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>

            <h1 class="text-xl font-bold text-center dark:text-white">Escala automática</h1>
        </div>
    </header>

    <!-- MAIN -->
    <main class="flex-1 px-5 max-w-2xl mx-auto">
        <div class="space-y-4">

            @forelse($escalasPorVaga as $vagaId => $escalas)
                @php $vaga = $escalas->first()->vaga; @endphp

                <div class="bg-white dark:bg-gray-800 rounded-3xl p-5 shadow-md border border-gray-200 dark:border-gray-700 w-[364px] h-[302px] max-w-md mx-auto hover:shadow-lg transition">

                    <!-- Data + Hora + Função -->
                    <div class="flex justify-between items-center mb-3">
                        <div class="text-center">
                            <p class="text-3xl font-bold text-gray-900 dark:text-white">
                                {{ \Carbon\Carbon::parse($escalas->first()->dataDiaria)->format('d') }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase -mt-1">
                                {{ \Carbon\Carbon::parse($escalas->first()->dataDiaria)->format('M') }}
                            </p>
                        </div>

                        <div class="flex flex-col">
                            <span class="text-sm text-gray-600 dark:text-gray-300">
                                {{ $escalas->first()->horario }}
                            </span>
                            <span class="font-bold text-gray-800 dark:text-gray-100">
                                {{ $vaga->tipoVaga }}
                            </span>
                        </div>
                    </div>

                    <hr class="my-3 border-gray-300 dark:border-gray-700">

                    <!-- Usuários atribuídos -->
                    <div class="flex items-center justify-between bg-gray-50 dark:bg-gray-700 p-3 rounded-2xl shadow-sm">
                        <div class="flex -space-x-2">
                            @foreach($escalas as $escala)
                                <img src="{{ $escala->user->fotoUser ? asset('storage/' . $escala->user->fotoUser) : asset('img/default-avatar.png') }}"
                                     class="w-8 h-8 rounded-full border-2 border-white dark:border-gray-800">
                            @endforeach
                        </div>

                        <button onclick="abrirModal(this)"
                                data-vaga-id="{{ $vagaId }}"
                                data-titulo="{{ addslashes($vaga->titulo) }}"
                                data-valor="{{ $vaga->valor_vaga }}"
                                data-users='@json($escalas->pluck("user")->toArray())'
                                class="p-2 rounded-full bg-white dark:bg-gray-600 shadow">
                            <img src="/img/info.svg">
                        </button>
                    </div>

                    <!-- Botão remover escala -->
                    <button onclick="removerEscala({{ $vagaId }}, {{ $empresa->id }})"
                            class="w-full flex items-center justify-center gap-2 bg-red-500 hover:bg-red-600 text-white font-semibold py-3 rounded-2xl transition mt-4">
                        <img src="/img/calendar-x-2.svg">
                        Cancelar Escala
                    </button>

                </div>

            @empty
                <div class="bg-white dark:bg-gray-800 rounded-lg p-8 text-center shadow">
                    <p class="text-gray-500 dark:text-gray-400">Nenhuma escala encontrada.</p>
                </div>
            @endforelse
        </div>
    </main>

    <!-- MODAL -->
    <div id="modal-info"
         class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden flex items-center justify-center z-50">

        <div class="bg-white dark:bg-gray-800 w-[90%] max-w-sm rounded-3xl p-5 shadow-xl">

            <!-- Cabeçalho -->
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="font-semibold text-gray-900 dark:text-white" id="modal-title">
                        Freelancers confirmados
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-400" id="modal-total">
                        -- 
                    </p>
                </div>

                <button onclick="fecharModal()"
                        class="p-2 rounded-full bg-gray-100 dark:bg-gray-700">
                    <svg class="w-5 h-5 text-gray-900 dark:text-white" fill="none"
                         stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Lista -->
            <div class="space-y-3" id="modal-lista"></div>

            <!-- Botão adicionar -->
            <button class="w-full mt-5 flex items-center justify-center gap-2 py-3 rounded-xl bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-200 font-semibold">
                <span>Adicionar mais freelancers</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
            </button>

        </div>
    </div>

    <!-- SCRIPTS -->
    <script>
        function abrirModal(button) {
            const vagaId = button.dataset.vagaId;
            const titulo = button.dataset.titulo;
            const valor = parseFloat(button.dataset.valor);
            const users = JSON.parse(button.dataset.users);

            document.getElementById("modal-title").textContent = titulo;

            const total = valor * users.length;
            document.getElementById("modal-total").textContent =
                "Total de gastos: R$ " + total.toFixed(2).replace(".", ",");

            const lista = document.getElementById("modal-lista");
            lista.innerHTML = "";

            users.forEach(user => {
                lista.innerHTML += `
                    <div class="flex items-center justify-between bg-blue-50 dark:bg-blue-900/20 p-3 rounded-xl">

                        <div class="flex items-center gap-3">
                            <img src="${user.fotoUser ? '/storage/' + user.fotoUser : '/img/default-avatar.png'}"
                                 class="w-10 h-10 rounded-full">
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white">${user.username}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Freelancer</p>
                            </div>
                        </div>

                      

                            <!-- BOTÃO AZUL COM X -->
                          <button onclick="removerUsuario(${user.id}, ${vagaId})"
    class="w-10 h-10 flex items-center justify-center 
           rounded-full bg-blue-500 hover:bg-blue-600 
           text-white text-xl font-bold transition">
    ×
</button>
                        </div>
                    </div>
                `;
            });

            document.getElementById("modal-info").classList.remove("hidden");
        }

        function fecharModal() {
            document.getElementById("modal-info").classList.add("hidden");
        }

        function removerUsuario(userId, vagaId) {
            if (confirm('Remover este usuário da escala?')) {
                fetch('/enterprises/remover-usuario-escala', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ user_id: userId, vaga_id: vagaId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Usuário removido da escala!');
                        location.reload();
                    } else {
                        alert('Erro: ' + (data.message || 'Falha ao remover.'));
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('Erro ao processar.');
                });
            }
        }

        function removerEscala(vagaId, empresaId) {
            if (confirm('Tem certeza que deseja remover a escala desta vaga? Isso removerá todas as escalas associadas.')) {
                fetch('/enterprises/remover-escala', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ vaga_id: vagaId, empresa_id: empresaId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Escala removida com sucesso!');
                        location.reload();
                    } else {
                        alert('Erro: ' + (data.message || 'Falha ao remover.'));
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('Erro ao processar a remoção.');
                });
            }
        }
    </script>

</body>
</html>
