<div x-data="skillsEditor(
        {{ json_encode($allSkills) }}, 
        {{ json_encode($mySkillsIds) }} 
     )"
     class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/90 backdrop-blur-sm p-4">

    <div class="relative w-full max-w-md bg-white dark:bg-gray-800 rounded-[40px] shadow-2xl overflow-hidden flex flex-col max-h-[90vh]"
    >

        

        <div class="px-6 pt-8 pb-2 text-center">
            <div class="flex justify-between items-center">
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">
                <span x-show="step === 1">Suas Habilidades</span>
                <span x-show="step > 1" x-text="'Sobre ' + currentSkillName"></span>
            </h2>

            <button @click="openModal = false" 
                    type="button" 
                    aria-label="Fechar" 
                    class="p-2 rounded-full text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition z-10">
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            </div>
            
            <div class="w-full bg-gray-200 rounded-full h-2 mt-4 dark:bg-gray-700 overflow-hidden">
                <div class="bg-sky-600 h-2 rounded-full transition-all duration-500 ease-out" 
                     :style="'width: ' + ((step / totalSteps) * 100) + '%'">
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-2">Passo <span x-text="step"></span> de <span x-text="totalSteps"></span></p>
        </div>

        <form @submit.prevent="submitForm" class="flex-1 overflow-y-auto px-6 py-4 flex flex-col gap-3">
            
            <div x-show="step === 1" x-transition class="grid grid-cols-2 gap-3">
                <template x-for="skill in allSkills" :key="skill.id">
                    <label class="relative flex items-center p-3 rounded-lg border cursor-pointer transition-colors"
                           :class="selectedSkills.includes(String(skill.id)) 
                                ? 'border-sky-500 bg-sky-50 dark:bg-sky-900/20 dark:border-sky-500' 
                                : 'border-gray-200 hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-700'">
                        
                        <input type="checkbox" :value="skill.id" x-model="selectedSkills" class="hidden">
                        
                        <div class="w-4 h-4 rounded border flex items-center justify-center mr-2 transition-colors"
                             :class="selectedSkills.includes(String(skill.id)) ? 'bg-sky-500 border-sky-500' : 'bg-gray-100 border-gray-300'">
                             <svg x-show="selectedSkills.includes(String(skill.id))" class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>

                        <span class="text-sm font-medium text-gray-900 dark:text-gray-300" x-text="skill.nomeHabilidade || skill.nome"></span>
                    </label>
                </template>
            </div>

            <div x-show="step > 1" x-transition class="space-y-4">
                <template x-if="currentQuestion">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4" x-text="currentQuestion.texto"></h3>
                        
                        <div class="space-y-2">
                            <template x-for="opcao in currentQuestion.opcoes" :key="opcao.id">
                                <label class="flex items-center p-4 rounded-2xl border cursor-pointer transition-all"
                                       :class="answers[currentQuestion.id] == opcao.id 
                                            ? 'border-sky-500 bg-sky-50 dark:bg-sky-900/30 dark:border-sky-500' 
                                            : 'border-gray-200 hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-700'">
                                    
                                    <input type="radio" :name="'q_' + currentQuestion.id" :value="opcao.id" x-model="answers[currentQuestion.id]" class="w-5 h-5 text-sky-600 focus:ring-sky-500">
                                    <span class="ml-3 text-gray-700 dark:text-gray-200 font-medium" x-text="opcao.texto"></span>
                                </label>
                            </template>
                        </div>
                    </div>
                </template>
            </div>

            <div class="mt-auto pt-4 flex flex-col gap-1">
                <x-btn-outline x-show="step > 1" @click="step--" type="button">
                    Voltar
                </x-btn-outline>

                <x-btn-primary @click="nextStep()" type="button">
                    <span x-show="loading">Salvando...</span>
                    <span x-show="!loading && step < totalSteps">Próximo</span>
                    <span x-show="!loading && step === totalSteps">Concluir</span>
                </x-btn-primary>
            </div>
        </form>
    </div>
</div>

<script>
    function skillsEditor(allSkillsData, initialSkills) {
        return {
            step: 1,
            loading: false,
            allSkills: allSkillsData,
            // AQUI ESTÁ O TRUQUE: Iniciamos com o que o user já tem
            selectedSkills: initialSkills.map(String),
            questionQueue: [],
            answers: {},

            get totalSteps() { return 1 + this.questionQueue.length; },
            get currentQuestion() { return this.step > 1 ? this.questionQueue[this.step - 2] : null; },
            get currentSkillName() {
                if(!this.currentQuestion) return '';
                const s = this.allSkills.find(skill => skill.id == this.currentQuestion.idHabilidade);
                return s ? (s.nomeHabilidade || s.nome) : '';
            },

            nextStep() {
                // Lógica idêntica ao seu Pós-Cadastro
                if (this.step === 1) {
                    if (this.selectedSkills.length === 0) {
                        alert('Selecione pelo menos uma habilidade.'); return;
                    }

                    this.questionQueue = [];
                    // Reconstrói a fila com BASE NO QUE ESTÁ MARCADO AGORA
                    this.selectedSkills.forEach(idStr => {
                        const skill = this.allSkills.find(s => s.id == idStr);
                        if (skill && skill.perguntas && skill.perguntas.length > 0) {
                            this.questionQueue.push(...skill.perguntas);
                        }
                    });
                }

                // Valida resposta
                if (this.step > 1) {
                    if (!this.answers[this.currentQuestion.id]) {
                        alert('Selecione uma opção.'); return;
                    }
                }

                // Navega ou Salva
                if (this.step < this.totalSteps) {
                    this.step++;
                } else {
                    this.submitForm();
                }
            },

            async submitForm() {
                this.loading = true;
                const formData = new FormData();
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                formData.append('_method', 'PATCH');

                // Envia TUDO o que está marcado (o controller faz o sync)
                this.selectedSkills.forEach(id => formData.append('habilidades[]', id));
                
                for (const [qId, opId] of Object.entries(this.answers)) {
                    formData.append(`respostas[${qId}]`, opId);
                }

                try {
                    const res = await fetch("{{ route('workers.update.skills') }}", {
                        method: 'POST',
                        headers: { 'Accept': 'application/json' },
                        body: formData
                    });

                    if (res.ok) {
                        window.location.reload();
                    } else {
                        alert('Erro ao salvar.');
                    }
                } catch (e) {
                    alert('Erro de conexão.');
                } finally {
                    this.loading = false;
                }
            }
        }
    }
</script>

<style>
    @keyframes scaleIn {
        from { opacity: 0; transform: scale(0.9); }
        to { opacity: 1; transform: scale(1); }
    }
    .animate-scaleIn {
        animation: scaleIn 0.2s ease-out forwards;
    }
</style>