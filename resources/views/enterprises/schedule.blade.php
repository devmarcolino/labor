<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Enterprise - Escalas</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="shortcut icon" href="../img/lb-blue.svg" type="image/x-icon">
    <style>
        [x-cloak] { display: none !important; }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
</head>

<x-flash-manager />

<body class="bg-gray-50 dark:bg-gray-900 min-h-screen transition-colors duration-500" 
      x-data="{ scheduleModalOpen: false, modalData: null }">

    <x-loading/>

    <!-- HEADER -->
    <div class="container mx-auto px-5 py-5 sm:py-9 max-w-md">
        <header class="flex justify-center w-full mx-auto pt-4 mb-2">
        <div class="flex items-center justify-between w-full max-w-2xl px-2 relative">
            <a href="{{ route('enterprises.dashboard') }}" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-800 transition-colors">
                <svg class="w-6 h-6 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            
            <h1 class="text-xl font-bold text-center dark:text-white">Escalas agendadas</h1>
        </div>
    </header>

        <!-- LISTA DE CARDS -->
        <div class="space-y-8">
            @forelse($escalasPorVaga as $vagaId => $escalas)
                @php 
                    $escalaPrincipal = $escalas->first();
                    $vaga = $escalaPrincipal->vaga; 
                    $isConfirmada = $escalaPrincipal->status === 'confirmada';
                    $dataObj = \Carbon\Carbon::parse($escalaPrincipal->dataDiaria);
                @endphp

                <div class="bg-white dark:bg-gray-800 rounded-[40px] shadow-labor border border-gray-100 dark:border-gray-700 overflow-hidden transition-all hover:shadow-lg relative group">
                    
                    <!-- HEADER DO CARD -->
                    <div class="px-5 pt-5 pb-3 flex justify-between items-start">
                        <div class="flex items-center gap-3">
                            <!-- Data Box (No lugar do avatar da empresa) -->
                            <div class="w-12 h-12 rounded-full bg-sky-50 dark:bg-sky-900/30 border border-sky-100 dark:border-sky-800 flex flex-col items-center justify-center text-sky-600 dark:text-sky-400 overflow-hidden flex-shrink-0">
                                <span class="text-[10px] font-bold uppercase">{{ $dataObj->format('M') }}</span>
                                <span class="text-lg font-extrabold leading-none">{{ $dataObj->format('d') }}</span>
                            </div>
                            
                            <div class="flex flex-col">
                                <h3 class="font-bold text-gray-900 dark:text-white text-lg leading-tight">
                                    {{ $vaga->tipoVaga ?? $vaga->funcVaga }}
                                </h3>
                                <span class="text-xs text-gray-400 font-medium">
                                    {{ $escalaPrincipal->horario }} • {{ $escalas->count() }} Pessoas
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
                                 class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 z-30 overflow-hidden py-1">
                                
                                <!-- OPÇÃO 1: CONFIRMAR/DESFAZER -->
                                <button onclick="confirmarEscala({{ $escalaPrincipal->id }}, '{{ addslashes($vaga->tipoVaga ?? $vaga->funcVaga) }}', {{ json_encode($escalas->pluck('user')) }})" 
                                        class="w-full text-left px-4 py-3 text-sm {{ $isConfirmada ? 'text-yellow-600' : 'text-green-600' }} hover:bg-gray-50 dark:hover:bg-gray-700 flex items-center gap-2 transition-colors">
                                    @if($isConfirmada)
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                        Desfazer Confirmação
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Confirmar Escala
                                    @endif
                                </button>
                                
                                <div class="h-px bg-gray-100 dark:bg-gray-700 my-1"></div>
                                
                                <!-- OPÇÃO 2: CANCELAR ESCALA -->
                                <button onclick="removerEscala({{ $vagaId }}, {{ $vaga->empresa->id }})"
                                        class="w-full text-left px-4 py-3 text-sm text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 flex items-center gap-2 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    Cancelar Escala
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- IMAGEM HERO -->
                    <div class="w-full h-48 bg-gray-200 relative group-hover:brightness-[0.98] transition-all">
                        <!-- Badge de Confirmada -->
                        @if($isConfirmada)
                            <div class="absolute inset-0 z-10 bg-gray-900/40 backdrop-blur-[1px] flex items-center justify-center">
                                <div class="bg-green-600/90 backdrop-blur-md px-4 py-1.5 rounded-full flex items-center gap-2 shadow-lg border border-white/20">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    <span class="text-sm font-bold text-white tracking-wide">CONFIRMADA</span>
                                </div>
                            </div>
                        @endif

                        @if(!empty($vaga->imgVaga))
                            <img src="{{ asset('storage/' . $vaga->imgVaga) }}" class="w-full h-full object-cover {{ $isConfirmada ? 'grayscale' : '' }}">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-gray-400 bg-gray-100 pattern-grid-lg">
                                <span class="text-xs opacity-50 font-medium uppercase tracking-wider">Sem capa</span>
                            </div>
                        @endif
                        
                        <!-- Preço -->
                        <div class="flex items-center absolute bottom-4 left-4 px-4 py-1.5 gap-2 bg-gray-900/60 backdrop-blur-md border border-white/10 rounded-full shadow-lg">
                            <span class="text-sm font-bold text-white">R$ {{ number_format($vaga->valor_vaga, 2, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- BODY INFO -->
                    <div class="p-5">

                        <!-- BOTÃO RODAPÉ (ABRIR MODAL) -->
                        <button @click="modalData = { 
                                    vaga: '{{ addslashes($vaga->tipoVaga ?? $vaga->funcVaga) }}', 
                                    users: {{ json_encode($escalas->pluck('user')) }},
                                    valor: {{ $vaga->valor_vaga }},
                                    vagaId: {{ $vagaId }}
                                }; scheduleModalOpen = true" 
                                class="w-full bg-gray-50 hover:bg-gray-100 dark:bg-gray-700/50 dark:hover:bg-gray-700 rounded-full p-3 flex items-center justify-between group transition-colors">
                            
                            <div class="flex items-center gap-3">
                                <div class="flex -space-x-3">
                                    @foreach($escalas->take(3) as $escala)
                                        <img src="{{ $escala->user->fotoUser ? asset('storage/' . $escala->user->fotoUser) : asset('img/default-avatar.png') }}"
                                             class="w-10 h-10 rounded-full border-2 border-white dark:border-gray-800 object-cover">
                                    @endforeach
                                    @if($escalas->count() > 3)
                                        <div class="w-10 h-10 rounded-full border-2 border-white bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600">
                                            +{{ $escalas->count() - 3 }}
                                        </div>
                                    @endif
                                </div>
                                <span class="text-sm font-bold text-gray-700 dark:text-white">Gerenciar Time</span>
                            </div>

                            <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-600 flex items-center justify-center text-gray-400 group-hover:text-sky-600 transition-colors shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </div>
                        </button>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center py-20 text-center">
                    <p class="text-gray-500">Nenhuma escala agendada.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- MODAL PADRONIZADA (Estilo Candidates) -->
    <div x-show="scheduleModalOpen" 
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/90 backdrop-blur-sm p-4"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        <div class="relative w-full max-w-md bg-white dark:bg-gray-800 rounded-[40px] overflow-hidden shadow-2xl flex flex-col max-h-[90vh]"
             @click.away="scheduleModalOpen = false">

            <!-- Modal Header -->
            <div class="p-6 pb-2 bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
                <div class="flex justify-between items-center mb-1">
                    <p class="text-sky-600 font-bold" x-text="modalData ? 'Total Previsto: R$ ' + (modalData.valor * modalData.users.length).toFixed(2).replace('.',',') : ''"></p>
                    <button @click="scheduleModalOpen = false" class="p-2 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                    
                </div>
                
            </div>

            <!-- Lista de Freelancers -->
            <div class="overflow-y-auto custom-scrollbar p-6 space-y-3 bg-white dark:bg-gray-800 flex-1">
                <template x-if="modalData">
                    <template x-for="user in modalData.users" :key="user.id">
                        <div class="flex items-center gap-4 p-3 rounded-full border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/20">
                            <img :src="user.fotoUser ? '/storage/' + user.fotoUser : '/img/default-avatar.png'" class="w-12 h-12 rounded-full object-cover">
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-900 dark:text-white" x-text="user.nome_real"></h4>
                                <p class="text-xs text-gray-500" x-text="modalData.vaga"></p>
                            </div>

                        </div>
                    </template>
                </template>
            </div>
        </div>
    </div>

    <!-- Scripts de Ação (Mantidos os originais, adicionado o confirmar) -->
    <!-- ========================================== -->
<!-- 1. SUA MODAL DE CONFIRMAÇÃO (REUTILIZÁVEL) -->
<!-- ========================================== -->
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

<!-- ========================================== -->
<!-- 2. SCRIPTS DE LÓGICA (COM FLASH MANAGER)   -->
<!-- ========================================== -->
<script>
    // --- CONTROLE DA MODAL ---
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
<!-- MODAL DE AVALIAÇÃO (Alpine.js) -->
<!-- ========================================== -->
<div x-data="avaliacaoModal()"
     x-show="isOpen"
     @open-avaliacao.window="abrirModal($event.detail)"
     x-cloak
     class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/90 backdrop-blur-sm p-4"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 scale-90"
     x-transition:enter-end="opacity-100 scale-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 scale-100"
     x-transition:leave-end="opacity-0 scale-90">

    <div class="bg-white dark:bg-gray-800 rounded-[35px] w-full max-w-md shadow-2xl overflow-hidden relative">
        
        <!-- Header -->
        <div class="p-6 text-center border-b border-gray-100 dark:border-gray-700">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Avaliar Freelancers</h3>
            <p class="text-sm text-gray-500 mt-1">Como foi o trabalho da equipe?</p>
        </div>

        <!-- Lista de Freelancers -->
        <div class="p-6 space-y-6 max-h-[60vh] overflow-y-auto custom-scrollbar">
            <template x-for="(user, index) in users" :key="user.id">
                <div class="bg-gray-50 dark:bg-gray-700/30 rounded-2xl p-4">
                    
                    <!-- Info do User -->
                    <div class="flex items-center gap-3 mb-3">
                        <img :src="user.fotoUser ? '/storage/' + user.fotoUser : '/img/default-avatar.png'" class="w-10 h-10 rounded-full object-cover">
                        <p class="font-bold text-gray-900 dark:text-white" x-text="user.username"></p>
                    </div>

                    <!-- Estrelas -->
                    <div class="flex justify-center gap-2 mb-3">
                        <template x-for="star in 5">
                            <button @click="setNota(index, star)" class="transition-transform active:scale-90">
                                <svg class="w-8 h-8" :class="star <= user.nota ? 'text-yellow-400 fill-current' : 'text-gray-300 dark:text-gray-600'" 
                                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                </svg>
                            </button>
                        </template>
                    </div>

                    <!-- Comentário (Opcional) -->
                    <textarea x-model="user.comentario" placeholder="Deixe um comentário (opcional)..." 
                              class="w-full text-sm bg-white dark:bg-gray-800 border-none rounded-xl p-3 focus:ring-1 focus:ring-sky-500 resize-none h-20 text-gray-700 dark:text-gray-200"></textarea>
                </div>
            </template>
        </div>

        <!-- Botão Enviar -->
        <div class="p-5 border-t border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800">
            <x-btn-primary @click="enviarAvaliacoes">
                <span x-show="!loading">Enviar Avaliações</span>
                <svg x-show="loading" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            </x-btn-primary>
        </div>

    </div>
</div>

<script>
    // --- LÓGICA DA MODAL DE AVALIAÇÃO ---
    function avaliacaoModal() {
        return {
            isOpen: false,
            users: [],
            escalaId: null,
            loading: false,

            abrirModal(detail) {
                this.escalaId = detail.escalaId;
                // Prepara os usuários adicionando campo de nota e comentário
                this.users = detail.users.map(u => ({ ...u, nota: 0, comentario: '' }));
                this.isOpen = true;
            },

            setNota(index, nota) {
                this.users[index].nota = nota;
            },

            enviarAvaliacoes() {
                // Filtra apenas quem recebeu nota
                const avaliacoes = this.users
                    .filter(u => u.nota > 0)
                    .map(u => ({ user_id: u.id, nota: u.nota, comentario: u.comentario }));

                if (avaliacoes.length === 0) {
                    alert('Por favor, dê uma nota para pelo menos um freelancer.');
                    return;
                }

                this.loading = true;

                fetch('/avaliar-freelancers', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ escala_id: this.escalaId, avaliacoes: avaliacoes })
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        this.isOpen = false;
                        window.dispatchEvent(new CustomEvent('notify', { detail: { type: 'success', title: 'Obrigado!', msg: 'Avaliações enviadas com sucesso.' } }));
                        setTimeout(() => location.reload(), 1500);
                    }
                })
                .finally(() => this.loading = false);
            }
        }
    }

    // --- ATUALIZAÇÃO DA FUNÇÃO CONFIRMAR ESCALA ---
    // Substitua a sua função antiga por esta versão melhorada
    function confirmarEscala(id, nomeVaga, usersJson) { // <--- Agora recebe os users
        
        // Se usersJson vier como string (às vezes acontece no blade), converte
        const users = (typeof usersJson === 'string') ? JSON.parse(usersJson) : usersJson;

        showConfirmModal(
            'Confirmar Escala',
            `Deseja confirmar o serviço de ${nomeVaga}? Isso irá gerar o pagamento.`,
            () => {
                // Fetch de Confirmação
                fetch(`/escala/${id}/confirmar`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        // SUCESSO! Agora abrimos a modal de avaliação em vez de reload direto
                        window.dispatchEvent(new CustomEvent('open-avaliacao', { 
                            detail: { escalaId: id, users: users } 
                        }));
                    } else {
                        alert('Erro ao confirmar.');
                    }
                });
            }
        );
    }
</script>
</body>
</html>