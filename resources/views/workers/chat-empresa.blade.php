<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Chat com {{ $empresa->nome_empresa }}</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <link rel="shortcut icon" href="../img/lb-blue.svg" type="image/x-icon">
</head>
<body class="bg-gray-50 flex flex-col h-screen">
    <header class="flex items-center gap-3 px-4 py-4 bg-white shadow-sm fixed top-0 left-0 w-full z-10" style="max-width: 100vw;">
        <a href="{{ route('workers.chat') }}" class="text-gray-500">
            <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <img src="{{ $empresa->fotoEmpresa ? asset('storage/' . $empresa->fotoEmpresa) : asset('img/default-avatar.png') }}" class="w-10 h-10 rounded-full object-cover">
        <div class="flex flex-col">
            <span class="font-bold text-gray-900">{{ $empresa->nome_empresa }}</span>
            <span class="text-xs text-gray-400">Hoje</span>
        </div>
    </header>
    <main id="chatMessages" class="flex-1 flex flex-col gap-2 px-4 py-6 overflow-y-auto bg-gray-50" style="margin-top:72px; margin-bottom:72px;">
        @foreach($mensagens as $msg)
            @php $isWorker = $msg->remetente_id === $worker->id && $msg->remetente_tipo === 'user'; @endphp
            <div class="flex {{ $isWorker ? 'justify-end' : 'justify-start' }} mb-2">
                @if(!$isWorker)
                    <img src="{{ $empresa->fotoEmpresa ? asset('storage/' . $empresa->fotoEmpresa) : asset('img/default-avatar.png') }}" class="w-7 h-7 rounded-full object-cover mr-2">
                @endif
                <div class="{{ $isWorker ? 'bg-sky-100 text-gray-800 text-right' : 'bg-white text-gray-800' }} rounded-2xl px-4 py-2 max-w-xs w-fit break-words shadow">
                    @if($msg->arquivo)
                        @php $ext = pathinfo($msg->arquivo, PATHINFO_EXTENSION); @endphp
                        @if(in_array(strtolower($ext), ['jpg','jpeg','png','gif','webp']))
                            <img src="{{ asset('storage/' . $msg->arquivo) }}" class="rounded-lg mb-2 max-w-[120px] h-auto object-cover">
                        @elseif(in_array(strtolower($ext), ['mp4','mov','avi','wmv']))
                            <video src="{{ asset('storage/' . $msg->arquivo) }}" controls class="rounded-lg mb-2 max-w-[160px] h-auto"></video>
                        @endif
                    @endif
                    @if($msg->mensagem)
                        <div style="word-break:break-word;white-space:pre-line;">{{ $msg->mensagem }}</div>
                    @endif
                    <div class="text-xs text-gray-400 mt-1">{{ \Carbon\Carbon::parse($msg->horario)->format('H:i') }}</div>
                </div>
            </div>
        @endforeach
    </main>
    <form id="chatForm" class="flex justify-center items-center gap-2 px-4 py-3 bg-white border-t fixed bottom-0 left-0 w-full max-w-2xl z-10">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <button type="button" id="mediaBtn" class="p-2 rounded-full hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-sky-200">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
        </button>
        <input type="file" name="arquivo" id="arquivoInput" accept="image/*,video/*" class="hidden">
        <input type="text" name="mensagem" id="mensagemInput" placeholder="Digite a mensagem..." class="flex-1 min-w-0 max-w-[60vw] rounded-full border border-gray-200 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-sky-200">
        <button type="submit" class="bg-sky-500 hover:bg-sky-600 text-white rounded-full p-2 transition">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M22 2L11 13"/><path stroke-linecap="round" stroke-linejoin="round" d="M22 2L15 22L11 13L2 9L22 2Z"/></svg>
        </button>
    </form>
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.0/dist/echo.iife.js"></script>
    <script>
            // Scroll imediato para a última mensagem ao abrir
            window.addEventListener('DOMContentLoaded', function() {
                var main = document.getElementById('chatMessages');
                if(main) main.scrollTop = main.scrollHeight;
            });
            window.Pusher = Pusher;
            const echo = new window.Echo({
                broadcaster: 'pusher',
                key: '{{ config('broadcasting.connections.pusher.key') }}',
                cluster: '{{ config('broadcasting.connections.pusher.cluster') }}',
                forceTLS: true,
            });
            const empresaId = {{ $empresa->id }};
            const workerId = {{ $worker->id }};
            const canal = `chat.${empresaId}.${workerId}`;
            function renderMensagens(mensagens) {
                const main = document.getElementById('chatMessages');
                main.innerHTML = '';
                mensagens.forEach(function(msg) {
                    const isWorker = msg.remetente_id === workerId && msg.remetente_tipo === 'user';
                    let avatar = isWorker ? '' : `<img src='{{ $empresa->fotoEmpresa ? asset('storage/' . $empresa->fotoEmpresa) : asset('img/default-avatar.png') }}' class='w-7 h-7 rounded-full object-cover mr-2'>`;
                    let arquivoHtml = '';
                    if (msg.arquivo) {
                        const ext = msg.arquivo.split('.').pop().toLowerCase();
                        if(['jpg','jpeg','png','gif','webp'].includes(ext)) {
                            arquivoHtml = `<img src='${msg.arquivo.startsWith('chat-midias') ? '/storage/' + msg.arquivo : msg.arquivo}' class='rounded-lg mb-2 max-w-[120px] h-auto object-cover'>`;
                        } else if(['mp4','mov','avi','wmv'].includes(ext)) {
                            arquivoHtml = `<video src='${msg.arquivo.startsWith('chat-midias') ? '/storage/' + msg.arquivo : msg.arquivo}' controls class='rounded-lg mb-2 max-w-[160px] h-auto'></video>`;
                        }
                    }
                    let mensagemHtml = msg.mensagem ? `<div style='word-break:break-word;white-space:pre-line;'>${msg.mensagem}</div>` : '';
                    let hora = new Date(msg.horario).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                    main.insertAdjacentHTML('beforeend', `
                        <div class='flex ${isWorker ? 'justify-end' : 'justify-start'} mb-2'>
                            ${avatar}
                            <div class='${isWorker ? 'bg-sky-100 text-gray-800 text-right' : 'bg-white text-gray-800'} rounded-2xl px-4 py-2 max-w-xs w-fit break-words shadow'>
                                ${arquivoHtml}
                                ${mensagemHtml}
                                <div class='text-xs text-gray-400 mt-1'>${hora}</div>
                            </div>
                        </div>
                    `);
                });
                // Scroll automático para a última mensagem
                setTimeout(() => { main.scrollTop = main.scrollHeight; }, 100);
            }
        function fetchMensagens() {
            fetch(`/workers/chat/${empresaId}/mensagens`)
                .then(res => res.json())
                .then(renderMensagens);
        }
        echo.private(canal)
            .listen('NovaMensagemEnviada', (e) => {
                // Renderiza a nova mensagem recebida sem recarregar a página
                const isWorker = e.remetente_id === workerId && e.remetente_tipo === 'user';
                let avatar = isWorker ? '' : `<img src='{{ $empresa->fotoEmpresa ? asset('storage/' . $empresa->fotoEmpresa) : asset('img/default-avatar.png') }}' class='w-7 h-7 rounded-full object-cover mr-2'>`;
                let arquivoHtml = '';
                if (e.arquivo) {
                    const ext = e.arquivo.split('.').pop().toLowerCase();
                    if(['jpg','jpeg','png','gif','webp'].includes(ext)) {
                        arquivoHtml = `<img src='${e.arquivo.startsWith('chat-midias') ? '/storage/' + e.arquivo : e.arquivo}' class='rounded-lg mb-2 max-w-[120px] h-auto object-cover'>`;
                    } else if(['mp4','mov','avi','wmv'].includes(ext)) {
                        arquivoHtml = `<video src='${e.arquivo.startsWith('chat-midias') ? '/storage/' + e.arquivo : e.arquivo}' controls class='rounded-lg mb-2 max-w-[160px] h-auto'></video>`;
                    }
                }
                let mensagemHtml = e.mensagem ? `<div style='word-break:break-word;white-space:pre-line;'>${e.mensagem}</div>` : '';
                let hora = new Date(e.horario).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                const html = `
                    <div class='flex ${isWorker ? 'justify-end' : 'justify-start'} mb-2'>
                        ${avatar}
                        <div class='${isWorker ? 'bg-sky-100 text-gray-800 text-right' : 'bg-white text-gray-800'} rounded-2xl px-4 py-2 max-w-xs w-fit break-words shadow'>
                            ${arquivoHtml}
                            ${mensagemHtml}
                            <div class='text-xs text-gray-400 mt-1'>${hora}</div>
                        </div>
                    </div>
                `;
                document.getElementById('chatMessages').insertAdjacentHTML('beforeend', html);
                document.getElementById('chatMessages').scrollTop = document.getElementById('chatMessages').scrollHeight;
            });
        // Atualiza a cada 10s para garantir sincronismo
        // Recarrega a página apenas ao receber nova mensagem via Pusher
        // Carrega ao abrir
        fetchMensagens();
        // Remover duplicidade de variáveis e listeners
        // MANTENHA APENAS UM BLOCO DE:
        const fileInput = document.getElementById('arquivoInput');
        document.getElementById('mediaBtn').addEventListener('click', function() {
            fileInput.click();
        });
        fileInput.addEventListener('change', function() {
            if (fileInput.files.length > 0) {
                document.getElementById('chatForm').dispatchEvent(new Event('submit', {cancelable: true}));
            }
        });
        document.getElementById('chatForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const input = document.getElementById('mensagemInput');
            const mensagem = input.value.trim();
            const arquivo = fileInput.files[0];
            const token = document.querySelector('input[name="_token"]').value;
            if (!mensagem && !arquivo) return;

            // Preview instantâneo da mensagem/mídia enviada pelo worker
            let html = '';
            if (arquivo) {
                const url = URL.createObjectURL(arquivo);
                if (arquivo.type.startsWith('image/')) {
                    html = `<div class="flex justify-end mb-2"><div class="bg-sky-100 text-gray-800 rounded-2xl px-4 py-2 max-w-xs shadow text-right"><img src="${url}" class="rounded-lg mb-2 max-w-full h-auto"><div>${mensagem}</div><div class="text-xs text-gray-400 mt-1">${new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</div></div></div>`;
                } else if (arquivo.type.startsWith('video/')) {
                    html = `<div class="flex justify-end mb-2"><div class="bg-sky-100 text-gray-800 rounded-2xl px-4 py-2 max-w-xs shadow text-right"><video src="${url}" controls class="rounded-lg mb-2 max-w-full h-auto"></video><div>${mensagem}</div><div class="text-xs text-gray-400 mt-1">${new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</div></div></div>`;
                }
            } else {
                html = `<div class="flex justify-end mb-2"><div class="bg-sky-100 text-gray-800 rounded-2xl px-4 py-2 max-w-xs shadow text-right"><div>${mensagem}</div><div class="text-xs text-gray-400 mt-1">${new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</div></div></div>`;
            }
            document.getElementById('chatMessages').insertAdjacentHTML('beforeend', html);
            document.getElementById('chatMessages').scrollTop = document.getElementById('chatMessages').scrollHeight;

            const formData = new FormData();
            formData.append('_token', token);
            formData.append('mensagem', mensagem);
            if (arquivo) formData.append('arquivo', arquivo);
            fetch(`{{ route('workers.chat.empresa', $empresa->id) }}`, {
                method: 'POST',
                body: formData
            });
            input.value = '';
            fileInput.value = '';
        });
    </script>
</body>
</html>
