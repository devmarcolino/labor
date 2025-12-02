<div x-data="{ show: false }"
     x-show="show"
     @open-candidates-modal.window="if ($event.detail.id === {{ $vaga->id }}) show = true" 
     x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/90 backdrop-blur-sm p-4"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">

    <div class="relative w-full max-w-md bg-white dark:bg-gray-800 rounded-[40px] overflow-hidden shadow-2xl flex flex-col max-h-[90vh]"
         @click.away="show = false">

        <div class="overflow-y-auto custom-scrollbar">
            
            @if($vaga->candidaturas->count() > 0)
                @php
                    // Pega o primeiro como "Melhor Match"
                    $melhorCandidato = $vaga->candidaturas->first()->user;
                    $outrosCandidatos = $vaga->candidaturas->skip(1);
                @endphp

                <div class="relative h-[500px] w-full group">
                    @if($melhorCandidato->fotoUser)
                        <img src="{{ asset('storage/' . $melhorCandidato->fotoUser) }}" class="absolute inset-0 w-full h-full object-cover">
                    @else
                        <div class="absolute inset-0 bg-gray-800 flex items-center justify-center text-white">
                            <svg class="w-20 h-20 opacity-20" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" /></svg>
                        </div>
                    @endif
                    
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/60 to-transparent"></div>

                    <div class="absolute top-3 right-3 left-3 max-w-2xl flex items-center gap-3 bg-gray-900/60 backdrop-blur-md border border-white/10 rounded-full px-6 py-3 shadow-lg">
                        <div class="rounded-full">
                            <img src="../img/ia.svg" alt="" class="w-6 h-6">
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-100 uppercase tracking-wider">Melhor Candidato</p>
                            <p class="text-[10px] text-gray-200 font-medium">Analisado por Labor IA ©</p>
                        </div>
                    </div>

                    <div class="absolute bottom-0 w-full p-6 text-white">
                        <div class="flex items-end justify-between mb-4">
                            <div>
                                <h2 class="text-3xl font-bold leading-tight">{{ explode(' ', $melhorCandidato->nome_real)[0] }}</h2>
                                <p class="text-gray-300 text-sm mt-1 flex items-center gap-2">
                                    <span class="bg-green-500/20 text-green-400 px-2 py-0.5 rounded text-xs font-bold border border-green-500/30">98% Match</span>
                                    <span class="text-gray-400">•</span>
                                    <span>{{ $melhorCandidato->endereco->cidade ?? 'Próximo' }}</span>
                                </p>
                            </div>
                        </div>

                        <x-btn-primary @click.prevent="
                            fetch('/vagas/curtir-candidato', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                                },
                                body: JSON.stringify({
                                    user_id: {{ $melhorCandidato->id }},
                                    vaga_id: {{ $vaga->id }}
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if(data.success) {
                                    show = false;
                                    window.dispatchEvent(new CustomEvent('notify', {detail: {type: 'success', title: 'Sucesso!', msg: 'Você curtiu {{ explode(' ', $melhorCandidato->nome_real)[0] }}!'}}));
                                } else {
                                    window.dispatchEvent(new CustomEvent('notify', {detail: {type: 'error', title: 'Erro', msg: data.message || 'Erro ao curtir candidato'}}));
                                }
                            })
                            .catch(() => {
                                window.dispatchEvent(new CustomEvent('notify', {detail: {type: 'error', title: 'Erro', msg: 'Erro ao curtir candidato'}}));
                            })
                        ">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Curtir candidato</span>
                        </x-btn-primary>
                    </div>
                </div>

                @if($outrosCandidatos->count() > 0)
                    <div class="p-6 bg-white dark:bg-gray-800">
                        <h3 class="text-gray-400 text-xs font-bold uppercase tracking-wider mb-4 ml-1">Outros Candidatos</h3>
                        
                        <div class="space-y-3">
                            @foreach($outrosCandidatos as $cand)
                                <div class="flex items-center gap-4 p-3 rounded-2xl border border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <img src="{{ $cand->user->fotoUser ? asset('storage/'.$cand->user->fotoUser) : asset('img/default-avatar.png') }}" class="w-12 h-12 rounded-full object-cover bg-gray-200">
                                    
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-bold text-gray-900 dark:text-white truncate">{{ $cand->user->nome_real }}</h4>
                                        <p class="text-xs text-gray-500">{{ $cand->user->endereco->cidade ?? 'Localização não inf.' }}</p>
                                    </div>

                                    <button @click="show = false; window.dispatchEvent(new CustomEvent('notify', {detail: {type: 'success', title: 'Sucesso!', msg: 'Vaga atribuída!'}}))"
                                            class="p-2 rounded-full bg-sky-50 text-sky-600 hover:bg-sky-600 hover:text-white transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            @else
                <div class="flex flex-col items-center justify-center py-20 px-6 text-center h-full">
                    <div class="w-24 h-24 bg-gray-50 dark:bg-gray-700 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Aguardando Candidatos</h3>
                    <p class="text-gray-500 text-sm max-w-[200px]">Assim que alguém se candidatar, a Labor IA fará a análise do perfil aqui.</p>
                </div>
            @endif

        </div>
    </div>
</div>