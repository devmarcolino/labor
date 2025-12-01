<div x-data="onboardingWorkerForm(@js($habilidades))" x-init="init()" 
     class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/90 backdrop-blur-sm p-4">

    <div class="relative w-full max-w-md rounded-[50px] bg-white pt-10 pb-6 px-6 shadow-2xl dark:bg-gray-800 flex flex-col max-h-[90vh] overflow-x-hidden">
        
        <div class="text-center mb-4">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                <span x-show="step === 1">Seu Perfil</span>
                <span x-show="isQuestionStep" x-text="'Sobre ' + currentSkillName"></span>
                <span x-show="step === totalSteps">Localização</span>
            </h2>
            
            <div class="w-full bg-gray-200 rounded-full h-2 mt-4 dark:bg-gray-700 overflow-hidden">
                <div class="bg-sky-600 h-2 rounded-full transition-all duration-500 ease-out" 
                     :style="'width: ' + ((step / totalSteps) * 100) + '%'">
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-2">Passo <span x-text="step"></span> de <span x-text="totalSteps"></span></p>
        </div>

        <form action="{{ route('workers.profile.update') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-3 px-1">
            @csrf
            @method('PATCH')

            <div x-show="step === 1" x-transition:enter="transition ease-out duration-300" class="space-y-4 py-2">
                
                <div class="flex flex-col items-center gap-3">
                    <div class="relative w-28 h-28 rounded-full bg-gray-100 border-2 border-dashed border-gray-400 hover:border-sky-500 flex items-center justify-center overflow-hidden group cursor-pointer transition-colors">
                        <input type="file" name="fotoUser" id="fotoUser" class="absolute inset-0 opacity-0 cursor-pointer z-20" accept="image/*" @change="previewPhoto">
                        <img x-show="photoPreview" :src="photoPreview" class="absolute inset-0 w-full h-full object-cover z-10">
                        <div x-show="!photoPreview" class="text-center p-2 z-0">
                            <span class="text-xs text-gray-500 font-medium">Foto</span>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Selecione suas Habilidades</label>
                    <div class="grid grid-cols-2 gap-2 max-h-40 overflow-y-auto p-1">
                        <template x-for="skill in allSkills" :key="skill.id">
                            <label class="relative flex items-center p-3 rounded-lg border border-gray-200 cursor-pointer hover:bg-sky-50 transition-colors dark:border-gray-700 dark:hover:bg-gray-700"
                                   :class="selectedSkills.includes(skill.id) ? 'border-sky-500 ring-1 ring-sky-500 bg-sky-50 dark:bg-gray-700' : ''">
                                
                                <input type="checkbox" 
                                       name="habilidades[]" 
                                       :value="skill.id" 
                                       x-model="selectedSkills" 
                                       class="w-4 h-4 text-sky-600 bg-gray-100 border-gray-300 rounded focus:ring-sky-500">
                                
                                <span class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300" x-text="skill.nomeHabilidade || skill.nome"></span>
                            </label>
                        </template>
                    </div>
                </div>
            </div>

            <div x-show="isQuestionStep" x-transition:enter="transition ease-out duration-300" class="space-y-4 py-2">
                
                <template x-if="currentQuestion">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4" x-text="currentQuestion.texto"></h3>
                        
                        <div class="space-y-2">
                            <template x-for="opcao in currentQuestion.opcoes" :key="opcao.id">
                                <label class="flex items-center p-4 rounded-2xl border border-gray-200 cursor-pointer hover:bg-sky-50 transition-all dark:border-gray-700 dark:hover:bg-gray-700"
                                       :class="answers[currentQuestion.id] == opcao.id ? 'border-sky-500 bg-sky-50 dark:bg-gray-600' : ''">
                                    
                                    <input type="radio" 
                                           :name="'respostas[' + currentQuestion.id + ']'" 
                                           :value="opcao.id"
                                           x-model="answers[currentQuestion.id]"
                                           class="w-5 h-5 text-sky-600 border-gray-300 focus:ring-sky-500">
                                           
                                    <span class="ml-3 text-gray-700 dark:text-gray-200 font-medium" x-text="opcao.texto"></span>
                                </label>
                            </template>
                        </div>
                    </div>
                </template>
            </div>

            <div x-show="step === totalSteps" x-transition:enter="transition ease-out duration-300" class="flex flex-col gap-3 py-2">
                <div>
                    <div @blur.capture="buscaCep()">
                        <x-input name="cep" x-model="cep" placeholder="CEP" required>CEP</x-input>
                    </div>
                    <p x-show="loading" class="text-xs text-sky-500 font-medium mt-1">Buscando...</p>
                </div>
                <div class="grid grid-cols-3 gap-3">
                    <div class="col-span-2"><x-input name="rua" x-model="rua" placeholder="Rua" required>Rua</x-input></div>
                    <div class="col-span-1"><x-input name="numero" placeholder="Nº" required>Nº</x-input></div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <x-input name="bairro" x-model="bairro" placeholder="Bairro" required>Bairro</x-input>
                    <x-input name="cidade" x-model="cidade" readonly class="bg-gray-100 text-gray-500">Cidade</x-input>
                </div>
                <input type="hidden" name="uf" x-model="uf">
            </div>

            <div class="mt-6 flex flex-col gap-1">
                <div x-show="step > 1">
                    <x-btn-outline type="button" @click="prevStep()" class="w-full justify-center">Voltar</x-btn-outline>
                </div>

                <div x-show="step < totalSteps">
                    <x-btn-primary type="button" @click="nextStep()" class="w-full justify-center">Próximo</x-btn-primary>
                </div>

                <div x-show="step === totalSteps">
                    <x-btn-primary type="submit">Finalizar</x-btn-primary>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function onboardingWorkerForm(dataBackend) {
    return {
        step: 1,
        allSkills: dataBackend,
        selectedSkills: [],
        questionQueue: [],
        answers: {},
        photoPreview: null, cep: '', rua: '', bairro: '', cidade: '', uf: '', loading: false,

        init() {},

        get totalSteps() { return 2 + this.questionQueue.length; },
        get isQuestionStep() { return this.step > 1 && this.step < this.totalSteps; },
        get currentQuestion() { return this.isQuestionStep ? this.questionQueue[this.step - 2] : null; },
        get currentSkillName() {
            if (!this.currentQuestion) return '';
            const skill = this.allSkills.find(s => s.id == this.currentQuestion.idHabilidade);
            return skill ? (skill.nomeHabilidade || skill.nome) : '';
        },

        showError(msg) {
            window.dispatchEvent(new CustomEvent('notify', {
                detail: { type: 'warning', title: 'Ops!', msg: msg }
            }));
        },

        nextStep() {
            if (this.step === 1) {
                const inputFoto = document.getElementById('fotoUser');
                if ((!inputFoto.files || inputFoto.files.length === 0) && !this.photoPreview) {
                    this.showError('Sua foto de perfil é obrigatória.'); return;
                }
                if (this.selectedSkills.length === 0) {
                    this.showError('Selecione pelo menos uma habilidade.'); return;
                }

                this.questionQueue = [];
                this.selectedSkills.forEach(skillId => {
                    const skill = this.allSkills.find(s => s.id == skillId);
                    if (skill && skill.perguntas) this.questionQueue.push(...skill.perguntas);
                });
            }
            
            if (this.isQuestionStep) {
                const pId = this.currentQuestion.id;
                if (!this.answers[pId]) {
                    this.showError('Por favor, selecione uma opção.'); return;
                }
            }
            this.step++;
        },

        prevStep() { this.step--; },

        previewPhoto(event) {
            const file = event.target.files[0];
            if(file) {
                const reader = new FileReader();
                reader.onload = (e) => this.photoPreview = e.target.result;
                reader.readAsDataURL(file);
            }
        },
        async buscaCep() {
            let cleanCep = this.cep.replace(/\D/g, '');
            if (cleanCep.length === 8) {
                this.loading = true;
                try {
                    let res = await fetch(`https://viacep.com.br/ws/${cleanCep}/json/`);
                    let data = await res.json();
                    if (!data.erro) {
                        this.rua = data.logradouro; this.bairro = data.bairro;
                        this.cidade = data.localidade; this.uf = data.uf;
                    } else {
                        this.showError('CEP não encontrado.');
                    }
                } catch(e){ this.showError('Erro ao buscar CEP.'); } finally { this.loading = false; }
            }
        }
    }
}
</script>