<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Labor for workers</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <link rel="shortcut icon" href="../img/lb-blue.svg" type="image/x-icon">
</head>
<x-flash-manager />
<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-500 h-screen flex flex-col">
    
    <x-loading/>

    <header class="flex justify-center w-full mx-auto pt-4 mb-2">
        <div class="flex items-center justify-between w-full max-w-2xl px-5 relative">
            <a href="{{ route('workers.dashboard') }}" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-800 transition-colors">
                <svg class="w-6 h-6 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            
            <h1 class="text-xl font-bold text-center dark:text-white">Escala automática</h1>
        </div>
    </header>

    <main class="flex-1 px-5 max-w-2xl mx-auto">
        <div class="space-y-4">
    @forelse($escalas as $escala)
        <div class="bg-white dark:bg-gray-800 rounded-3xl p-5 shadow-md border border-gray-200 dark:border-gray-700 w-[364px] h-[302px]
                    max-w-md mx-auto hover:shadow-lg transition">

            <!-- Data + Hora + Função -->
            <div class="flex justify-between items-center mb-3">
                <div class="text-center">
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">
                        {{ \Carbon\Carbon::parse($escala->dataDiaria)->format('d') }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase -mt-1">
                        {{ \Carbon\Carbon::parse($escala->dataDiaria)->format('M') }}
                    </p>
                </div>

                <div class="flex flex-col">
                    <span class="text-sm text-gray-600 dark:text-gray-300">
                        {{ $escala->horario }}
                    </span>
                    <span class="font-bold text-gray-800 dark:text-gray-100">
                        {{ $escala->vaga->tipoVaga }}
                    </span>
                </div>
            </div>

            <hr class="my-3 border-gray-300 dark:border-gray-700">

            <!-- Empresa Card -->
            <div class="flex items-center justify-between bg-gray-50 dark:bg-gray-700 p-3 rounded-2xl shadow-sm">
                <div class="flex items-center gap-3">
                    <img src="{{ $escala->empresa && $escala->empresa->fotoEmpresa ? asset('storage/' . $escala->empresa->fotoEmpresa) : asset('img/default-avatar.png') }}"
                         class="w-10 h-10 rounded-full border-2 border-white dark:border-gray-800 object-cover" alt="Empresa">
                    <div>
                        <p class="text-gray-800 dark:text-gray-100 font-semibold">
                            {{ $escala->empresa->nome_empresa }}
                        </p>
                        <p class="text-xs text-gray-600 dark:text-gray-400">
                            {{ $escala->empresa->ramo ?? 'Ramo não informado' }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 -mt-1">
                            Ver informações
                        </p>
                    </div>
                </div>

                <button class="p-2 rounded-full bg-white dark:bg-gray-600 shadow"
                        onclick="abrirModalEmpresa({{ $escala->empresa->id }})">
                    <img src="/img/info.svg" alt="info">
                </button>
            </div>

            <!-- Botão de Desistir -->
            <button onclick="desistirEscala({{ $escala->id }}, {{ $escala->idVaga }}, {{ $escala->idUser }})"
    class="w-full flex items-center justify-center gap-2 bg-red-500 hover:bg-red-600 
           text-white font-semibold py-3 rounded-2xl transition mt-4">
    <img src="/img/calendar-x-2.svg" alt="Desistir Icon">
    Desistir
</button>

        </div>
    @empty
        <div class="bg-white dark:bg-gray-800 rounded-lg p-8 text-center shadow">
            <p class="text-gray-500 dark:text-gray-400">Nenhuma escala encontrada.</p>
        </div>
    @endforelse
</div>

    </main>

    <!-- MODAL EMPRESA -->
   <!-- MODAL EMPRESA -->
<div id="modal-empresa"
     class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden flex items-center justify-center z-50">

    <div class="bg-white dark:bg-gray-800 w-[90%] max-w-sm rounded-3xl p-5 shadow-xl relative">

        <!-- CARD SUPERIOR -->
        <div class="flex items-center gap-3 mb-4">
            <img id="modal-empresa-foto"
                 src="/img/default-avatar.png"
                 class="w-12 h-12 rounded-full object-cover border-2 border-gray-200 dark:border-gray-700">

            <div>
                <p class="font-semibold text-gray-900 dark:text-white text-base" id="modal-empresa-nome">Empresa</p>
                <p class="text-sm text-gray-500 dark:text-gray-300" id="modal-empresa-ramo">Ramo</p>
            </div>

            <button onclick="fecharModalEmpresa()"
                class="absolute top-3 right-3 p-1.5 rounded-full bg-gray-100 dark:bg-gray-700">
                <svg class="w-5 h-5 text-gray-900 dark:text-white" fill="none" stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- ENDEREÇO -->
        <p class="text-sm text-gray-600 dark:text-gray-300 mb-3" id="modal-empresa-endereco"></p>

        <!-- INFORMAÇÕES -->
        <div class="space-y-2 bg-gray-50 dark:bg-gray-700 p-4 rounded-2xl">
            <p class="text-sm text-gray-700 dark:text-gray-300">
                <span class="font-semibold">Telefone:</span>
                <span id="modal-empresa-telefone">--</span>
            </p>

            <p class="text-sm text-gray-700 dark:text-gray-300">
                <span class="font-semibold">Email:</span>
                <span id="modal-empresa-email">--</span>
            </p>

            <p class="text-sm text-gray-700 dark:text-gray-300">
                <span class="font-semibold">Horário:</span>
                {{ $escala->horario }}
            </p>
        </div>

        <button onclick="fecharModalEmpresa()"
            class="w-full mt-5 flex items-center justify-center gap-2 py-3 rounded-xl bg-blue-600 text-white font-semibold">
            Fechar
        </button>
    </div>
</div>


    <script>
        function abrirModalEmpresa(empresaId) {
            fetch(`/workers/empresa-info/${empresaId}`)
              .then(res => res.json())
              .then(data => {
                  if (!data.success) {
                      alert(data.message || 'Falha ao carregar informações da empresa');
                      return;
                  }
                  document.getElementById('modal-empresa-nome').textContent = data.nome;
                  document.getElementById('modal-empresa-endereco').textContent = (data.endereco && data.endereco.completo) ? data.endereco.completo : 'Endereço não informado';
                  document.getElementById('modal-empresa-telefone').textContent = data.telefone || '--';
                  document.getElementById('modal-empresa-email').textContent = data.email || '--';
                  document.getElementById('modal-empresa-ramo').textContent = data.ramo || 'Ramo não informado';
                  document.getElementById('modal-empresa-foto').src = data.foto || '/img/default-avatar.png';
                  document.getElementById('modal-empresa').classList.remove('hidden');
              })
              .catch(err => {
                  console.error('Erro ao carregar empresa:', err);
                  alert('Erro ao carregar informações da empresa');
              });
        }

        function fecharModalEmpresa() {
            document.getElementById('modal-empresa').classList.add('hidden');
        }
        function desistirEscala(escalaId, vagaId, userId) {
            if (confirm('Tem certeza que deseja desistir desta vaga? Isso removerá a candidatura, curtida e escala.')) {
                console.log('Enviando:', { escala_id: escalaId, vaga_id: vagaId, user_id: userId });
                fetch('/workers/desistir-vaga', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ escala_id: escalaId, vaga_id: vagaId, user_id: userId })
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Data:', data);
                    if (data.success) {
                        alert('Desistência realizada com sucesso!');
                        location.reload(); // Recarregar a página para atualizar a lista
                    } else {
                        alert('Erro: ' + (data.message || 'Falha ao desistir.'));
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('Erro ao processar a desistência.');
                });
            }
        }
    </script>
</body>
</html>