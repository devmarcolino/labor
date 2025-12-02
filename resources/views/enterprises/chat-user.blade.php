<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Labor for enterprises</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <link rel="shortcut icon" href="../img/lb-blue.svg" type="image/x-icon">
</head>
<body>
<div class="flex flex-col h-screen bg-gray-50">
    <header class="flex items-center gap-3 px-4 py-4 bg-white shadow-sm fixed top-0 left-0 w-full z-10" style="max-width: 100vw;">
        <a href="{{ route('enterprises.chat') }}" class="text-gray-500">
            <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <img src="{{ $user->fotoUser ? asset('storage/' . $user->fotoUser) : asset('img/default-avatar.png') }}" class="w-10 h-10 rounded-full object-cover">
        <div class="flex flex-col">
            <span class="font-bold text-gray-900">{{ $user->nome_real }}</span>
            <span class="text-xs text-gray-400">Hoje</span>
        </div>
    </header>
    <main id="chatMessages" class="flex-1 flex flex-col gap-2 px-4 py-6 overflow-y-auto bg-gray-50" style="margin-top:72px; margin-bottom:72px;">
        @foreach($mensagens as $msg)
            @if($msg->remetente_id === $empresa->id && $msg->remetente_tipo === 'empresa')
                <div class="flex justify-end mb-2">
                    <div class="bg-sky-100 text-gray-800 rounded-2xl px-4 py-2 max-w-xs w-fit break-words shadow text-right">
                        <div style="word-break:break-word;white-space:pre-line;">{{ $msg->mensagem }}</div>
                        <div class="text-xs text-gray-400 mt-1">{{ \Carbon\Carbon::parse($msg->horario)->format('H:i') }}</div>
                    </div>
                </div>
            @else
                <div class="flex justify-start mb-2">
                    <img src="{{ $user->fotoUser ? asset('storage/' . $user->fotoUser) : asset('img/default-avatar.png') }}" class="w-7 h-7 rounded-full object-cover mr-2">
                    <div class="bg-white text-gray-800 rounded-2xl px-4 py-2 max-w-xs w-fit break-words shadow">
                        <div style="word-break:break-word;white-space:pre-line;">{{ $msg->mensagem }}</div>
                        <div class="text-xs text-gray-400 mt-1">{{ \Carbon\Carbon::parse($msg->horario)->format('H:i') }}</div>
                    </div>
                </div>
            @endif
        @endforeach
    </main>
    <form id="chatForm" class="flex items-center gap-2 px-4 py-3 bg-white border-t fixed bottom-0 left-0 w-full z-10" style="max-width: 100vw;">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="text" name="mensagem" id="mensagemInput" placeholder="Digite a mensagem..." class="flex-1 rounded-full border border-gray-200 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-sky-200">
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

    // Envio AJAX do formulário
    document.getElementById('chatForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const input = document.getElementById('mensagemInput');
        const mensagem = input.value.trim();
        if (!mensagem) return;
        const token = document.querySelector('input[name="_token"]').value;

        // Exibe a mensagem enviada instantaneamente
        let html = `<div class=\"flex justify-end mb-2\"><div class=\"bg-sky-100 text-gray-800 rounded-2xl px-4 py-2 max-w-xs shadow text-right\"><div>${mensagem}</div><div class=\"text-xs text-gray-400 mt-1\">${new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</div></div></div>`;
        document.querySelector('main').insertAdjacentHTML('beforeend', html);
        document.querySelector('main').scrollTop = document.querySelector('main').scrollHeight;
        input.value = '';

        fetch(`{{ route('enterprises.chat.user', $user->id) }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ mensagem })
        });
    });
</script>
</body>
</html>
