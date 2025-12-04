<!DOCTYPE html>
<html lang="pt-BR">
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
<body>
<div class="flex flex-col h-screen bg-gray-50">
    <header class="flex items-center justify-between px-4 py-4 bg-white shadow-sm fixed top-0 left-0 w-full z-10" style="max-width: 100vw;">
        <div class="flex items-center gap-3">
            <a href="{{ route('enterprises.chat') }}" class="text-gray-500">
                <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <img src="{{ $user->fotoUser ? asset('storage/' . $user->fotoUser) : asset('img/default-avatar.png') }}" class="w-10 h-10 rounded-full object-cover">
            <div class="flex flex-col">
                <span class="font-bold text-gray-900">{{ $user->username }}</span>
                <span class="text-xs text-gray-400">Hoje</span>
            </div>
        </div>
        <img src="{{ asset('img/button.svg') }}" class="w-15 h-15 cursor-pointer" onclick="scaleUser({{ $user->id }}, {{ $vaga->id }})" alt="Escalar">
    </header>
    <main id="chatMessages" class="flex-1 flex flex-col gap-2 px-4 py-6 overflow-y-auto bg-gray-50" style="margin-top:72px; margin-bottom:72px;">
        @foreach($mensagens as $msg)
            @php $isEmpresa = $msg->remetente_id === $empresa->id && $msg->remetente_tipo === 'empresa'; @endphp
            <div class="flex {{ $isEmpresa ? 'justify-end' : 'justify-start' }} mb-2">
                @if(!$isEmpresa)
                    <img src="{{ $user->fotoUser ? asset('storage/' . $user->fotoUser) : asset('img/default-avatar.png') }}" class="w-7 h-7 rounded-full object-cover mr-2">
                @endif
                <div class="{{ $isEmpresa ? 'bg-sky-100 text-gray-800 text-right' : 'bg-white text-gray-800' }} rounded-2xl px-4 py-2 max-w-xs w-fit break-words shadow">
                    @if($msg->arquivo)
                        @php
                            $ext = pathinfo($msg->arquivo, PATHINFO_EXTENSION);
                        @endphp
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
        <script>
                // Scroll imediato para a última mensagem ao abrir
                window.addEventListener('DOMContentLoaded', function() {
                    var main = document.getElementById('chatMessages');
                    if(main) main.scrollTop = main.scrollHeight;
                });
            // Scroll automático para a última mensagem ao carregar
            window.addEventListener('DOMContentLoaded', function() {
                setTimeout(function() {
                    var main = document.getElementById('chatMessages');
                    if(main) main.scrollTop = main.scrollHeight;
                }, 100);
            });
        </script>
    </main>
    <form id="chatForm" class="flex justify-center max-w-full items-center gap-2 px-4 py-3 bg-white border-t fixed bottom-0 left-0 w-full z-10">
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
</div>
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.0/dist/echo.iife.js"></script>
<script>
    // Configuração do Echo/Pusher
    window.Pusher = Pusher;
    const echo = new window.Echo({
        broadcaster: 'pusher',
        key: '{{ config('broadcasting.connections.pusher.key') }}',
        cluster: '{{ config('broadcasting.connections.pusher.cluster') }}',
        forceTLS: true,
    });

    // Canal privado do chat
    const empresaId = {{ $empresa->id }};
    const userId = {{ $user->id }};
    const canal = `chat.${empresaId}.${userId}`;

    echo.private(canal)
        .listen('NovaMensagemEnviada', (e) => {
            // Monta o HTML da mensagem recebida
            let html = '';
            if (e.remetente_id === empresaId && e.remetente_tipo === 'empresa') {
                html = `<div class=\"flex justify-end mb-2\"><div class=\"bg-sky-100 text-gray-800 rounded-2xl px-4 py-2 max-w-xs shadow text-right\"><div>${e.mensagem}</div><div class=\"text-xs text-gray-400 mt-1\">${new Date(e.horario).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</div></div></div>`;
            } else {
                html = `<div class=\"flex justify-start mb-2\"><img src=\"{{ $user->fotoUser ? asset('storage/' . $user->fotoUser) : asset('img/default-avatar.png') }}\" class=\"w-7 h-7 rounded-full object-cover mr-2\"><div class=\"bg-white text-gray-800 rounded-2xl px-4 py-2 max-w-xs shadow\"><div>${e.mensagem}</div><div class=\"text-xs text-gray-400 mt-1\">${new Date(e.horario).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</div></div></div>`;
            }
            document.querySelector('main').insertAdjacentHTML('beforeend', html);
            document.querySelector('main').scrollTop = document.querySelector('main').scrollHeight;
        });

    // Atualiza a cada 10s para garantir sincronismo
    // Recarrega a página apenas ao receber nova mensagem via Pusher
    // Envio AJAX do formulário
    // Botão de mídia aciona input file
    // Removido setInterval para evitar delay, atualização só via Pusher

    const fileInput = document.getElementById('arquivoInput');
    document.getElementById('mediaBtn').addEventListener('click', function() {
        fileInput.click();
    });

    // Ao escolher arquivo, envia o formulário automaticamente
    fileInput.addEventListener('change', function() {
        if (fileInput.files.length > 0) {
            document.getElementById('chatForm').dispatchEvent(new Event('submit', {cancelable: true}));
        }
    });

    document.getElementById('chatForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const input = document.getElementById('mensagemInput');
        const fileInput = document.getElementById('arquivoInput');
        const mensagem = input.value.trim();
        const arquivo = fileInput.files[0];
        const token = document.querySelector('input[name="_token"]').value;
        if (!mensagem && !arquivo) return;

        // Exibe a mensagem/imagem/vídeo instantaneamente
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
        document.querySelector('main').insertAdjacentHTML('beforeend', html);
        document.querySelector('main').scrollTop = document.querySelector('main').scrollHeight;
        input.value = '';
        fileInput.value = '';

        const formData = new FormData();
        formData.append('_token', token);
        formData.append('mensagem', mensagem);
        if (arquivo) formData.append('arquivo', arquivo);

        fetch(`{{ route('enterprises.chat.user', [$user->id, $vaga->id]) }}`, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
            },
            body: formData
        });
    </script>

<!-- Modal de Confirmação -->
<div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-sm w-full mx-4">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Confirmar Escala</h3>
        <p class="text-gray-700 dark:text-gray-300 mb-6">Tem certeza que deseja escalar este usuário para esta vaga?</p>
        <div class="flex justify-end gap-3">
            <button id="confirmNo" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">Não</button>
            <button id="confirmYes" class="px-4 py-2 bg-sky-500 text-white rounded-lg hover:bg-sky-600 transition">Sim</button>
        </div>
    </div>
</div>
</body>
</html>
