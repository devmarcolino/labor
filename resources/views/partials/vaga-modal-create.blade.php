<div x-data="jobCreateForm()" 
     x-show="openVagaModal" 
     x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-90 backdrop-blur-sm p-4"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">

    <div class="relative w-full max-w-md rounded-[50px] bg-white pt-10 pb-6 px-6 shadow-2xl dark:bg-gray-800 flex flex-col max-h-[90vh] overflow-x-hidden">
        
        <div class="text-center mb-4 relative">
            <div class="flex items-center justify-center">
            <button @click="openVagaModal = false" 
                    type="button" 
                    aria-label="Fechar" 
                    class="absolute right-4 p-2 rounded-full text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition z-10">
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                <span x-show="step === 1">Nova Vaga</span>
                <span x-show="step === 2">Detalhes</span>
                <span x-show="step === 3">Imagem</span>
            </h2>
            </div>
            
            <div class="w-full bg-gray-200 rounded-full h-2 mt-4 dark:bg-gray-700 overflow-hidden">
                <div class="bg-sky-600 h-2 rounded-full transition-all duration-500 ease-out" 
                     :style="'width: ' + (step * 33.3) + '%'">
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-2">Passo <span x-text="step"></span> de 3</p>
        </div>

        <form action="{{ route('enterprises.vagas.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col  px-1">
            @csrf

            <div x-show="step === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0" class="space-y-4 py-2">
                
                <div>
                    <x-input x-model="fields.tipoVaga" name="tipoVaga" placeholder="Ex: Garçom Noturno" required>
                        Título da Vaga
                    </x-input>
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Habilidade Necessária</label>
                    <select x-model="fields.funcVaga" name="funcVaga" class="bg-gray-50/85 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-sky-500 focus:border-sky-500 block w-full p-2.5 dark:bg-gray-700/85 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        <option value="">Selecione uma habilidade</option>
                        @foreach($skills as $skill)
                            <option value="{{ $skill->id }}">{{ $skill->nomeHabilidade }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div x-show="step === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0" class="space-y-4 py-2">
                
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <x-input x-ref="moneyInput" name="valor_vaga" placeholder="R$ 0,00" required>
                            Valor
                        </x-input>
                    </div>
                    <div class="gap-3">

                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Horário (Início e Fim)</label>
                            
                            <div class="flex items-center bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus-within:ring-1.5 focus-within:ring-sky-500 focus-within:border-sky-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white overflow-hidden">
                                
                                <input type="time" x-model="fields.hora_inicio" name="hora_inicio" class="bg-transparent border-none focus:ring-0 w-full p-2.5 text-center appearance-none" required>
                                
                                <span class="text-gray-400 font-bold px-1">às</span>
                                
                                <input type="time" x-model="fields.hora_fim" name="hora_fim" class="bg-transparent border-none focus:ring-0 w-full p-2.5 text-center appearance-none" required>
                                
                            </div>
                        </div>

                    </div>
                </div>

                <div class="mt-3">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Data do Trabalho</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"><path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/></svg>
                        </div>
                        <input id="dataVagaInput" placeholder="Selecione a data" 
            autocomplete="off"
            readonly
            required type="text" name="dataVaga" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-sky-500 focus:border-sky-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Selecione a data" required>
                    </div>
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Descrição</label>
                    <textarea x-model="fields.descVaga" name="descVaga" rows="3" class="block p-3 w-full text-sm bg-gray-50/85 border border-gray-300 rounded-lg focus:ring-sky-500 focus:border-sky-500 dark:bg-gray-700/85 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Detalhes da vaga..." required></textarea>
                </div>
            </div>

            <div x-show="step === 3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0" class="space-y-4 py-2">
                
                <div class="flex flex-col items-center gap-3">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Imagem de Capa</label>
                    
                    <div class="relative w-full h-40 bg-gray-100 border-2 border-dashed border-gray-400 hover:border-sky-500 rounded-xl flex items-center justify-center overflow-hidden group cursor-pointer transition-colors">
                        
                        <input type="file" name="imgVaga" id="imgVaga" class="absolute inset-0 opacity-0 cursor-pointer z-20" accept="image/*" @change="previewPhoto" required>
                        
                        <img x-show="photoPreview" :src="photoPreview" class="absolute inset-0 w-full h-full object-cover z-10">

                        <div x-show="!photoPreview" class="text-center p-2 z-0">
                            <svg class="w-8 h-8 mx-auto text-gray-400 group-hover:text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span class="text-xs text-gray-500 font-medium block mt-1">Carregar Imagem</span>
                        </div>
                    </div>
                    
                    <p x-show="photoPreview" class="text-xs text-sky-600 cursor-pointer hover:underline" @click="photoPreview = null; document.getElementById('imgVaga').value = ''">Remover foto</p>
                </div>
            </div>

            <div class="mt-6 flex flex-col w-full gap-3 pt-2 dark:border-gray-700">
                
                <div x-show="step > 1" class="flex-1">
                    <x-btn-outline type="button" @click="step--">
                        Voltar
                    </x-btn-outline>
                </div>

                <div x-show="step < 3" class="w-full">
                    <x-btn-primary type="button" @click="nextStep()">
                        Próximo
                    </x-btn-primary>
                </div>

                <div x-show="step === 3" class="flex-1">
                    <x-btn-primary type="submit">
                        Publicar Vaga
                    </x-btn-primary>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function jobCreateForm() {
    return {
        step: 1,
        photoPreview: null,
        
        fields: { 
            tipoVaga: '', funcVaga: '', valor_vaga: '', dataVaga: '', 
            hora_inicio: '', hora_fim: '', descVaga: '' 
        },

        init() {
            // 1. Inicia Máscara de Dinheiro
            if(this.$refs.moneyInput) {
                IMask(this.$refs.moneyInput, {
                    mask: 'R$ num',
                    blocks: { num: { mask: Number, thousandsSeparator: '.', radix: ',' } }
                });
            }

            // 2. Inicia o Datepicker MANUALMENTE (Sem duplicidade)
            const dataEl = document.getElementById('dataVagaInput');
            if (dataEl && typeof Datepicker !== 'undefined') {
                const dp = new Datepicker(dataEl, {
                    autohide: true,
                    format: 'dd/mm/yyyy',
                    language: 'pt-BR', // Garante português
                    minDate: new Date(), // Bloqueia passado
                });
                
                // O "Pulo do Gato": Quando mudar a data, atualiza o Alpine
                dataEl.addEventListener('changeDate', (e) => {
                    this.fields.dataVaga = dataEl.value;
                });
            }
        },

        nextStep() {
            // Função auxiliar para disparar erro
            const showError = (msg) => {
                window.dispatchEvent(new CustomEvent('notify', {
                    detail: { type: 'warning', title: 'Atenção', msg: msg }
                }));
            };

            // Validação Step 1
            if (this.step === 1) {
                if (!this.fields.tipoVaga || !this.fields.funcVaga) { 
                    showError('Por favor, preencha o título e selecione a habilidade.'); 
                    return; 
                }
            }

            // Validação Step 2
            if (this.step === 2) {
                const valor = this.$refs.moneyInput.value;
                
                const dataReal = document.getElementById('dataVagaInput').value;
                this.fields.dataVaga = dataReal;

                if (!valor || !this.fields.descVaga || !this.fields.hora_inicio || !this.fields.hora_fim) { 
                    showError('Preencha todos os detalhes, valor e horários.'); 
                    return; 
                }
                
                if(!this.fields.dataVaga) {
                     showError('Selecione a data do trabalho.');
                     return;
                }
            }

            this.step++;
        },

        previewPhoto(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => { this.photoPreview = e.target.result; };
                reader.readAsDataURL(file);
            }
        }
    }
}
</script>