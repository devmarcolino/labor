<div x-data="candidatesModal()"
     x-show="show"
     @open-candidates-modal.window="openModal($event.detail.id)" 
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

        <div x-show="isLoading" class="flex flex-col items-center justify-center py-20 h-full">
            <svg class="animate-spin h-10 w-10 text-sky-600 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            <p class="text-gray-500 font-medium">Carregando candidatos...</p>
        </div>

        <div x-show="!isLoading && data && data.melhor" class="overflow-y-auto custom-scrollbar h-full bg-white dark:bg-gray-800">
            
            <template x-if="data && data.melhor">
                <div>
                    <div class="relative h-[500px] w-full group">
                        <img :src="data.melhor.foto || '/img/default-avatar.png'" class="absolute inset-0 w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black via-black/60 to-transparent"></div>

                        

                        <div class="absolute bottom-0 w-full p-6 text-white">
                            <div class="mb-4">
                                <h2 class="text-3xl font-bold leading-tight" x-text="data.melhor.nome"></h2>
                                <p class="text-gray-300 text-sm mt-1 flex items-center gap-2">
                                    <span class="px-2 py-0.5 rounded text-xs font-bold border"
                                          :class="getMatchColor(data.melhor.porcentagem)"
                                          x-text="data.melhor.porcentagem + '% Match'"></span>
                                    <span class="text-gray-400">•</span>
                                    <span x-text="data.melhor.cidade"></span>
                                </p>
                            </div>
                            
                            <button @click="aprovarCandidato(data.melhor.id, currentVagaId)"
 class="w-full bg-sky-600 hover:bg-sky-500 text-white font-bold py-3 rounded-full shadow-lg transition-all flex justify-center items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Curtir candidato
                            </button>
                        </div>
                    </div>

                    <template x-if="data.candidatos && data.candidatos.length > 1">
                        <div class="p-6">
                            <h3 class="text-gray-400 text-xs font-bold uppercase mb-4">Outros Candidatos</h3>
                            <div class="space-y-3">
                                <template x-for="cand in data.candidatos.slice(1)" :key="cand.id">
                                    <div class="flex items-center gap-4 p-3 rounded-2xl border border-gray-100 dark:border-gray-700">
                                        <img :src="cand.foto || '/img/default-avatar.png'" class="w-12 h-12 rounded-full object-cover">
                                        <div class="flex-1">
                                            <div class="flex justify-between">
                                                <h4 class="font-bold text-gray-900 dark:text-white" x-text="cand.nome"></h4>
                                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full border"
                                                      :class="getMatchColor(cand.porcentagem)"
                                                      x-text="cand.porcentagem + '%'"></span>
                                            </div>
                                            <p class="text-xs text-gray-500" x-text="cand.cidade"></p>
                                        </div>
                                        <button @click="aprovarCandidato(cand.id, currentVagaId)" class="p-2 rounded-full bg-sky-50 text-sky-600 hover:bg-sky-100">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </template>
        </div>

        <div x-show="!isLoading && (!data || !data.melhor)" class="flex flex-col items-center justify-center h-[300px] text-center p-6">
            <p class="text-gray-500">Nenhum candidato encontrado para esta vaga.</p>
        </div>
    </div>
</div>

<script>
function candidatesModal() {
    return {
        show: false,
        isLoading: false,
        data: null,
        currentVagaId: null,

        async openModal(vagaId) {
    this.currentVagaId = vagaId;
    this.show = true;
    this.isLoading = true;
    this.data = null;

    try {
        const res = await fetch(`/enterprise/api/vaga/${vagaId}/candidatos`);

        if (res.ok) {
            const json = await res.json();
            console.log("Dados recebidos:", json);

            if (json.candidatos && json.candidatos.length > 0) {

                // MELHOR CANDIDATO
                const melhor = [...json.candidatos]
                    .sort((a, b) => b.match_percent - a.match_percent)[0];

                this.data = {
                    vaga: json.vaga,
                    melhor: {
                        id: melhor.id,
                        nome: melhor.nome,
                        cidade: melhor.cidade,
                        foto: melhor.foto,
                        porcentagem: melhor.match_percent
                    },
                    candidatos: json.candidatos.map(c => ({
                        id: c.id,
                        nome: c.nome,
                        cidade: c.cidade,
                        foto: c.foto,
                        porcentagem: c.match_percent
                    }))
                };

            } else {
                this.data = null;
            }

        } else {
            console.error("Erro API:", res.status);
        }
    } catch (e) {
        console.error(e);
        window.dispatchEvent(new CustomEvent('notify', { detail: { type: 'error', msg: 'Erro ao carregar' }}));
    } finally {
        this.isLoading = false;
    }
},


        // Restante das funções iguais...
        getMatchColor(score) {
            if (!score) return 'bg-gray-100 text-gray-500 border-gray-200';
            if (score >= 70) return 'bg-green-100 text-green-700 border-green-200';
            if (score >= 40) return 'bg-yellow-100 text-yellow-700 border-yellow-200';
            return 'bg-red-50 text-red-600 border-red-100';
        },

        curtir(userId) {
            fetch('/enterprise/invite', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    user_id: userId,
                    vaga_id: this.currentVagaId
                })
            })
            .then(r => r.json())
            .then(data => {
                if(data.success) {
                    this.show = false;
                    window.dispatchEvent(new CustomEvent('notify', {detail: {type: 'success', title: 'Sucesso!', msg: 'Candidato convidado!'}}));
                }
            });
        }
    }
}
</script>