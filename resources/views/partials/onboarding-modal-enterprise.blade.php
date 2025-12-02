<div x-data="onboardingEnterpriseForm()" x-init="init()" 
     class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/90 backdrop-blur-sm p-4">

    <div class="relative w-full max-w-md rounded-[50px] bg-white pt-10 pb-6 px-6 shadow-2xl dark:bg-gray-800 flex flex-col max-h-[90vh] overflow-x-hidden">
        
        <div class="text-center mb-4">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                <span x-show="step === 1">Perfil da Empresa</span>
                <span x-show="step === 2">Endereço Comercial</span>
            </h2>
            
            <div class="w-full bg-gray-200 rounded-full h-2 mt-4 dark:bg-gray-700 overflow-hidden">
                <div class="bg-sky-600 h-2 rounded-full transition-all duration-500 ease-out" 
                     :style="'width: ' + (step * 50) + '%'">
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-2">Passo <span x-text="step"></span> de 2</p>
        </div>

        <form action="{{ route('enterprises.profile.update') }}" method="POST" enctype="multipart/form-data" class="flex flex-col px-1">
            @csrf
            @method('PATCH')

            <div x-show="step === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0" class="space-y-4 py-2">
                
                <div class="flex flex-col items-center gap-3">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Logótipo da Empresa</label>
                    
                    <div class="relative w-32 h-32 rounded-full bg-gray-100 border-2 border-dashed border-gray-400 hover:border-sky-500 flex items-center justify-center overflow-hidden group cursor-pointer transition-colors">
                        <input type="file" name="fotoEmpresa" id="fotoEmpresa" class="absolute inset-0 opacity-0 cursor-pointer z-20" accept="image/*" @change="previewPhoto">
                        
                        <img x-show="photoPreview" :src="photoPreview" class="absolute inset-0 w-full h-full object-cover z-10">

                        <div x-show="!photoPreview" class="text-center p-2 z-0">
                            <svg class="w-8 h-8 mx-auto text-gray-400 group-hover:text-sky-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span class="text-xs text-gray-500 font-medium block mt-1 group-hover:text-sky-500">Carregar</span>
                        </div>
                    </div>
                    <p x-show="photoPreview" class="text-xs text-sky-600 cursor-pointer hover:underline" @click="document.getElementById('fotoEmpresa').click()">Alterar logo</p>
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Sobre a Empresa</label>
                    <textarea name="desc_empresa" id="desc_empresa" rows="4" class="block p-3 w-full text-sm text-gray-900 bg-gray-50 rounded-2xl border border-gray-300 focus:ring-sky-500 focus:border-sky-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Fale um pouco sobre a cultura da empresa e o que fazem..." required></textarea>
                </div>
            </div>

            <div x-show="step === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0" class="space-y-3 py-2">
                <div>
                    <div @blur.capture="buscaCep()">
                        <x-input id="cepEnterprise" name="cep" x-model="cep" placeholder="CEP (00000-000)" required>CEP</x-input>
                    </div>
                    <p x-show="loading" class="text-xs text-sky-500 font-medium mt-1">A procurar endereço...</p>
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div class="col-span-2">
                        <x-input name="rua" x-model="rua" placeholder="Rua" required>Rua</x-input>
                    </div>
                    <div class="col-span-1">
                        <x-input name="numero" placeholder="Nº" required>Nº</x-input>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <x-input name="bairro" x-model="bairro" placeholder="Bairro" required>Bairro</x-input>
                    <x-input name="cidade" x-model="cidade" readonly class="bg-gray-100 text-gray-500 cursor-not-allowed">Cidade</x-input>
                </div>
                
                <input type="hidden" name="uf" x-model="uf">
            </div>

            <div class="mt-6 flex flex-col w-full gap-1 pt-2">
                
                <div x-show="step > 1" class="flex-1">
                    <x-btn-outline type="button" @click="step--" class="w-full justify-center">
                        Voltar
                    </x-btn-outline>
                </div>

                <div x-show="step === 1" class="w-full">
                    <x-btn-primary type="button" @click="validateStep1()" class="w-full">
                        Próximo Passo
                    </x-btn-primary>
                </div>

                <div x-show="step === 2" class="flex-1">
                    <x-btn-primary type="submit" class="w-full">
                        Finalizar Cadastro
                    </x-btn-primary>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function onboardingEnterpriseForm() {
    return {
        step: 1,
        photoPreview: null,
        cep: '', rua: '', bairro: '', cidade: '', uf: '', loading: false,

        init() {
            // APLICA A MÁSCARA AQUI
            const el = document.getElementById('cepEnterprise');
            if(el) IMask(el, { mask: '00000-000' });
        },



        showError(msg) {
            window.dispatchEvent(new CustomEvent('notify', {
                detail: { type: 'warning', title: 'Faltam dados', msg: msg }
            }));
        },

        previewPhoto(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => { this.photoPreview = e.target.result; };
                reader.readAsDataURL(file);
            }
        },

        validateStep1() {
            const inputFoto = document.getElementById('fotoEmpresa');
            const desc = document.getElementById('desc_empresa').value;
            
            if ((!inputFoto.files || inputFoto.files.length === 0) && !this.photoPreview) {
                this.showError('Adicione o logotipo da empresa.');
                return;
            }
            
            if (!desc.trim()) {
                this.showError('A descrição da empresa é obrigatória.');
                return;
            }
            
            this.step = 2;
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
                        this.showError('CEP inválido.');
                    }
                } catch (e) { this.showError('Erro de conexão.'); } finally { this.loading = false; }
            }
        }
    }
}
</script>