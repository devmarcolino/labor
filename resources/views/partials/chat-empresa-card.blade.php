<a href="{{ route('workers.chat.empresa', $empresa->id) }}" class="flex items-center bg-white shadow-md rounded-full px-4 py-3 gap-3 w-full max-w-xs hover:bg-gray-100 transition">
    <img src="{{ $empresa->fotoEmpresa ? asset('storage/' . $empresa->fotoEmpresa) : asset('img/default-avatar.png') }}" class="w-12 h-12 rounded-full object-cover">
    <div class="flex-1 min-w-0">
        <div class="flex items-center justify-between">
            <span class="font-bold text-gray-900">{{ $empresa->nome_empresa }}</span>
            <span class="text-xs text-gray-400">{{ $ultimaMensagem ? $ultimaMensagem->horario->format('H:i') : '' }}</span>
        </div>
        <div class="text-gray-400 text-sm truncate">{{ $ultimaMensagem->mensagem ?? 'Mensagem...' }}</div>
    </div>
    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
</a>
