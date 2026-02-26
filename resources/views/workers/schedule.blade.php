<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Worker - Minhas Escalas</title>

        <link rel="manifest" href="/manifest.webmanifest">
        <meta name="theme-color" content="#0ea5e9">
        <meta name="mobile-web-app-capable" content="yes">
        <link rel="apple-touch-icon" href="/img/auth-worker.png">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="shortcut icon" href="../img/lb-blue.svg" type="image/x-icon">
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>

<x-flash-manager />

<!-- MUDANÇA: Usamos uma função dedicada workerSchedule() no x-data -->
<body class="bg-gray-50 dark:bg-gray-900 min-h-screen transition-colors duration-500"
      x-data="workerSchedule()">
    
    <x-loading/>

    <div class="container mx-auto px-5 py-5 sm:py-9 max-w-md">
        <header class="flex justify-center w-full mx-auto pt-4 mb-2">
        <div class="flex items-center justify-between w-full max-w-2xl px-5 relative">
            <a href="{{ route('enterprises.dashboard') }}" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-800 transition-colors">
                <svg class="w-6 h-6 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            
            <h1 class="text-xl font-bold text-center dark:text-white">Minhas escalas</h1>
        </div>
    </header>

        <div class="space-y-8">
            @forelse($escalas as $escala)
                @php $dataObj = \Carbon\Carbon::parse($escala->dataDiaria); @endphp

                <div class="bg-white dark:bg-gray-800 rounded-[40px] shadow-labor border border-gray-100 dark:border-gray-700 overflow-hidden transition-all hover:shadow-lg relative group">

                    <!-- HEADER DO CARD -->
                    <div class="px-5 pt-5 pb-3 flex justify-between items-start">
                        <div class="flex items-center gap-3">
                            <!-- Logo da Empresa -->
                            <div class="w-12 h-12 rounded-full bg-gray-50 border border-gray-100 flex items-center justify-center overflow-hidden flex-shrink-0">
                                @if($escala->empresa && $escala->empresa->fotoEmpresa)
                                    <img src="{{ asset('storage/' . $escala->empresa->fotoEmpresa) }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-xs font-bold text-gray-400">{{ substr($escala->empresa->nome_empresa, 0, 2) }}</span>
                                @endif
                            </div>
                            
                            <div class="flex flex-col">
                                <h3 class="font-bold text-gray-900 dark:text-white text-lg leading-tight">
                                    {{ $escala->empresa->nome_empresa }}
                                </h3>
                                <span class="text-xs text-gray-400 font-medium">
                                    {{ $escala->empresa->ramo ?? 'Empresa Parceira' }}
                                </span>
                            </div>
                        </div>

                        <!-- MENU 3 PONTINHOS -->
                        <div class="relative" x-data="{ openMenu: false }">
                            <button @click="openMenu = !openMenu" class="p-2 -mr-2 rounded-full text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 10c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0-6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 12c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>
                            </button>

                            <div x-show="openMenu" @click.away="openMenu = false" x-cloak 
                                 x-transition.origin.top.right
                                 class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 z-30 overflow-hidden py-1">
                                
                                <button onclick="desistirEscala({{ $escala->id }}, {{ $escala->idVaga }}, {{ $escala->idUser }})"
                                        class="w-full text-left px-4 py-3 text-sm text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 flex items-center gap-2 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    Desistir da Vaga
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- HERO IMAGE -->
                    <div class="w-full h-48 bg-gray-200 relative group-hover:brightness-[0.98] transition-all">
                        @if(!empty($escala->vaga->imgVaga))
                            <img src="{{ asset('storage/' . $escala->vaga->imgVaga) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-gray-400 bg-gray-100 pattern-grid-lg">
                                <span class="text-xs opacity-50 font-medium uppercase tracking-wider">Sem capa</span>
                            </div>
                        @endif

                        <!-- Badge de Data -->
                        <div class="absolute top-4 right-4 bg-white/90 dark:bg-gray-900/90 backdrop-blur-md px-3 py-1 rounded-xl shadow-lg border border-white/20 flex flex-col items-center">
                            <span class="text-xs font-bold uppercase text-gray-500">{{ $dataObj->format('M') }}</span>
                            <span class="text-xl font-extrabold text-gray-900 dark:text-white leading-none">{{ $dataObj->format('d') }}</span>
                        </div>
                        
                        <!-- Horário -->
                        <div class="flex items-center absolute bottom-4 left-4 px-4 py-1.5 gap-2 bg-gray-900/60 backdrop-blur-md border border-white/10 rounded-full shadow-lg">
                            <svg class="w-4 h-4 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="text-sm font-bold text-white">{{ $escala->horario }}</span>
                        </div>
                    </div>

                    <!-- BODY INFO -->
                    <div class="p-5">
                        <div class="mb-4">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white leading-tight mb-1">
                                {{ $escala->vaga->tipoVaga }}
                            </h2>
                            <p class="text-sm text-gray-500 leading-relaxed">
                                Você está escalado para esta função. Toque abaixo para ver detalhes da empresa.
                            </p>
                        </div>

                        <!-- MUDANÇA: @click chamando a função do Alpine -->
                        <button @click="openCompanyModal({{ $escala->empresa->id }})"
                                class="w-full bg-gray-50 hover:bg-gray-100 dark:bg-gray-700/50 dark:hover:bg-gray-700 rounded-full p-3 flex items-center justify-between group transition-colors">
                            
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-sky-100 dark:bg-sky-900/30 text-sky-600 flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                </div>
                                <span class="text-sm font-bold text-gray-700 dark:text-white">Ver Empresa</span>
                            </div>

                            <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-600 flex items-center justify-center text-gray-400 group-hover:text-sky-600 transition-colors shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </div>
                        </button>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center py-20 text-center">
                    <p class="text-gray-500">Você não tem escalas agendadas.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- MODAL EMPRESA (Estilo Padronizado) -->
    <div x-show="companyModalOpen" 
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/90 backdrop-blur-sm p-4"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        <div class="relative w-full max-w-sm bg-white dark:bg-gray-800 rounded-[40px] overflow-hidden shadow-2xl flex flex-col"
             @click.away="companyModalOpen = false">

            <!-- Skeleton de Carregamento enquanto companyData é nulo -->
            <div x-show="isLoading" class="p-10 flex justify-center">
                <svg class="animate-spin h-8 w-8 text-sky-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            </div>

            <template x-if="!isLoading && companyData">
                <div>
                    <!-- Capa/Header Modal -->
                    <div class="p-6 pb-2 border-b border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800">
                        <div class="flex items-center gap-4 mb-4">
                            <img :src="companyData.foto || '/img/default-avatar.png'" class="w-16 h-16 rounded-full object-cover border-4 border-gray-50 dark:border-gray-700">
                            <div>
                                <h2 class="text-xl font-bold text-gray-900 dark:text-white leading-tight" x-text="companyData.nome"></h2>
                                <p class="text-sm text-gray-500" x-text="companyData.ramo || 'Ramo não informado'"></p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Info List -->
                    <div class="p-6 space-y-4 bg-white dark:bg-gray-800">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-500 flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 font-bold uppercase">Endereço</p>
                                <p class="text-sm text-gray-700 dark:text-gray-300" x-text="(companyData.endereco && companyData.endereco.completo) ? companyData.endereco.completo : 'Não informado'"></p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-500 flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 font-bold uppercase">Contato</p>
                                <p class="text-sm text-gray-700 dark:text-gray-300" x-text="companyData.telefone || '--'"></p>
                                <p class="text-xs text-gray-500" x-text="companyData.email || '--'"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Botão Fechar -->
                    <div class="p-4 bg-gray-50 dark:bg-gray-900/50">
                        <x-btn-outline @click="companyModalOpen = false">
                            Fechar
                        </x-btn-outline>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <div id="confirmModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-[999] hidden transition-opacity duration-300">
    <div class="bg-white dark:bg-gray-800 rounded-[45px] px-8 pt-10 pb-8 max-w-sm w-full mx-4 shadow-2xl transform transition-all scale-100">
        
        <h3 id="modalTitle" class="text-xl font-bold text-gray-900 dark:text-white mb-3 text-center">
            Confirmar Ação
        </h3>
        
        <p id="modalDesc" class="text-gray-600 dark:text-gray-300 mb-8 text-center leading-relaxed">
            Tem certeza?
        </p>

        <div class="flex flex-col gap-1">
            <!-- Botão Confirmar -->
            <x-btn-primary id="confirmYes">
                Confirmar
            </x-btn-primary>
            
            <!-- Botão Cancelar -->
            <x-btn-outline id="confirmNo">
                Cancelar
            </x-btn-outline>
        </div>
    </div>
</div>
    <!-- SCRIPTS MISTURADOS (Alpine + Vanilla) -->
    <script>
        // Lógica Alpine para a Modal
        function workerSchedule() {
            return {
                companyModalOpen: false,
                companyData: null,
                isLoading: false,

                openCompanyModal(empresaId) {
                    this.companyModalOpen = true;
                    this.isLoading = true;
                    this.companyData = null;

                    fetch(`/workers/empresa-info/${empresaId}`)
                        .then(res => res.json())
                        .then(data => {
                            if (!data.success) {
                                alert('Erro ao carregar');
                                this.companyModalOpen = false;
                                return;
                            }
                            this.companyData = data;
                        })
                        .catch(err => {
                            console.error(err);
                            alert('Erro de conexão');
                            this.companyModalOpen = false;
                        })
                        .finally(() => {
                            this.isLoading = false;
                        });
                }
            }
        }

        // Lógica Vanilla para Desistir (fora do Alpine)
        let onConfirmAction = null; // Guarda a função que será executada

    const modal = document.getElementById('confirmModal');
    const btnYes = document.getElementById('confirmYes');
    const btnNo = document.getElementById('confirmNo');
    const titleEl = document.getElementById('modalTitle');
    const descEl = document.getElementById('modalDesc');

    // Função genérica para abrir a modal
    function showConfirmModal(title, text, actionCallback, confirmColorClass = 'bg-sky-500') {
        titleEl.textContent = title;
        descEl.textContent = text;
        onConfirmAction = actionCallback;

        modal.classList.remove('hidden');
    }

    // Fechar Modal
    btnNo.onclick = () => { modal.classList.add('hidden'); };

    // Executar Ação
    btnYes.onclick = () => {
        if (onConfirmAction) onConfirmAction();
        modal.classList.add('hidden');
    };

    // --- FUNÇÕES ESPECÍFICAS (EMPRESA & WORKER) ---

    // 1. Confirmar Escala (Empresa)
    function confirmarEscala(id) {
        showConfirmModal(
            'Confirmar Escala?',
            'Isso irá gerar o registro financeiro e confirmar o profissional.',
            () => executarFetch(`/escala/${id}/confirmar`, { method: 'POST' }, 'Escala atualizada com sucesso!')
        );
    }

    // 2. Remover Escala Inteira (Empresa)
    function removerEscala(vagaId, empresaId) {
        showConfirmModal(
            'Cancelar Escala?',
            'Tem certeza que deseja cancelar TODAS as escalas desta vaga? Essa ação é irreversível.',
            () => executarFetch('/enterprises/remover-escala', {
                method: 'POST',
                body: JSON.stringify({ vaga_id: vagaId, empresa_id: empresaId })
            }, 'Escala cancelada!'),
        );
    }

    // 3. Desistir da Vaga (Worker)
    function desistirEscala(escalaId, vagaId, userId) {
        showConfirmModal(
            'Desistir da Vaga?',
            'Você perderá sua vaga nesta escala. Tem certeza?',
            () => executarFetch('/workers/desistir-vaga', {
                method: 'POST',
                body: JSON.stringify({ escala_id: escalaId, vaga_id: vagaId, user_id: userId })
            }, 'Você desistiu da vaga.'),
        );
    }

    // --- HELPER DE FETCH (PADRÃO PARA TODOS) ---
    function executarFetch(url, options = {}, successMsg) {
        // Adiciona headers padrão
        const headers = {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        };

        fetch(url, { ...options, headers: headers })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Dispara o Flash Manager (assumindo que ele ouve evento 'notify')
                window.dispatchEvent(new CustomEvent('notify', { 
                    detail: { type: 'success', title: 'Sucesso', msg: successMsg } 
                }));
                
                // Recarrega após breve delay para ver a animação
                setTimeout(() => location.reload(), 1000);
            } else {
                window.dispatchEvent(new CustomEvent('notify', { 
                    detail: { type: 'error', title: 'Erro', msg: data.message || 'Ocorreu um erro.' } 
                }));
            }
        })
        .catch(err => {
            console.error(err);
            window.dispatchEvent(new CustomEvent('notify', { 
                detail: { type: 'error', title: 'Erro Conexão', msg: 'Verifique sua internet ou contate o suporte.' } 
            }));
        });
    }
    </script>

    <!-- ========================================== -->
<!-- 1. SUA MODAL DE CONFIRMAÇÃO (REUTILIZÁVEL) -->
<!-- ========================================== -->
</body>
</html>