@props(['candidatosCurtidos'])
<div class="space-y-2">
    @forelse($candidatosCurtidos as $curtido)
        @php
            $worker = $curtido->user;
        @endphp
        <div class="flex items-center gap-3 p-3 rounded-xl bg-white dark:bg-gray-800 shadow hover:bg-gray-100 dark:hover:bg-gray-700 transition">
            <img src="{{ $worker->fotoUser ? asset('storage/'.$worker->fotoUser) : asset('img/default-avatar.png') }}" class="w-10 h-10 rounded-full object-cover bg-gray-200">
            <div class="flex-1 min-w-0">
                <div class="font-bold text-gray-900 dark:text-white truncate">{{ $worker->nome_real }}</div>
                <div class="text-xs text-gray-500 truncate">{{ $worker->email }}</div>
            </div>
            @if($curtido->vaga)
                <span class="text-xs text-sky-600 font-bold bg-sky-100 rounded px-2 py-0.5">{{ $curtido->vaga->titulo ?? 'Vaga' }}</span>
            @endif
        </div>
    @empty
        <div class="text-gray-400 text-center py-8">Nenhum candidato curtido ainda.</div>
    @endforelse
</div>
