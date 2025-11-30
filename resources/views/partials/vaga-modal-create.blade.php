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
            <button @click="openVagaModal = false" class="absolute -top-6 -right-2 text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                <span x-show="step === 1">Nova Vaga</span>
                <span x-show="step === 2">Detalhes</span>
                <span x-show="step === 3">Imagem</span>
            </h2>
            
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
                    <select x-model="fields.funcVaga" name="funcVaga" class="bg-gray-50/85 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-sky-500 block w-full p-2.5 dark:bg-gray-700/85 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
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
                    <div>
                        <x-input x-model="fields.horario" name="horario" placeholder="Ex: 18:00 - 23:00" required>
                            Horário
                        </x-input>
                    </div>
                </div>

                <div>
                    <x-input x-model="fields.dataVaga" type="date" name="dataVaga" x-min="minDate" required>
                        Data do Trabalho
                    </x-input>
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

            <div class="mt-6 flex w-full gap-3 pt-2 dark:border-gray-700">
                
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
        minDate: new Date().toISOString().split('T')[0], // Pega a data de hoje (YYYY-MM-DD)
        
        fields: { 
            tipoVaga: '', 
            funcVaga: '', 
            valor_vaga: '', 
            dataVaga: '', 
            horario: '',
            descVaga: '' 
        },

        init() {
            // Inicializa a máscara de Dinheiro no input x-ref="moneyInput"
            // Requer IMask importado no app.js
            if(this.$refs.moneyInput) {
                IMask(this.$refs.moneyInput, {
                    mask: 'R$ num',
                    blocks: {
                        num: {
                            mask: Number,
                            thousandsSeparator: '.',
                            radix: ','
                        }
                    }
                });
            }
        },

        nextStep() {
            // Validação
            if (this.step === 1) {
                if (!this.fields.tipoVaga || !this.fields.funcVaga) { 
                    alert('Preencha o título e a habilidade.'); return; 
                }
            }
            if (this.step === 2) {
                // Pega o valor do input mascarado direto do DOM (pois o x-model pode não pegar a máscara)
                const valor = this.$refs.moneyInput.value;
                if (!valor || !this.fields.dataVaga || !this.fields.descVaga || !this.fields.horario) { 
                    alert('Preencha todos os detalhes, valor e horário.'); return; 
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