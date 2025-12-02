<div x-data="addressEditor({{ json_encode($addressData) }})"
     class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/90 backdrop-blur-sm p-4">

    <div class="relative w-full max-w-md bg-white dark:bg-gray-800 rounded-[40px] shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
        
        <button @click="openModal = false" class="absolute top-5 right-6 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 z-10">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        <div class="px-6 pt-8 pb-2 text-center">
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">Atualizar Endereço</h2>
            <p class="text-xs text-gray-400 mt-2">Digite seu CEP para buscar automaticamente</p>
        </div>

        <form @submit.prevent="submitForm" class="flex-1 overflow-y-auto px-6 py-4 flex flex-col gap-4">
            
            <div>
                <x-input x-ref="cepInput" name="cep" x-model="form.cep" @blur="buscaCep()" placeholder="00000-000" required>
                  CEP
                </x-input>
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div class="col-span-2">
                  <x-input name="Rua" x-model="form.rua" required>
                    Rua
                  </x-input>
                </div>
                <div>
                  <x-input name="numero" x-model="form.numero" required>
                    Número
                  </x-input>
                </div>
            </div>

            <div>
                <x-input name="bairro" x-model="form.bairro" required>
                  Bairro
                </x-input>
            </div>

            <div class="grid grid-cols-4 gap-3">
                <div class="col-span-3">
                    <x-input name="Cidade" x-model="form.cidade" readonly>
                      Cidade
                    </x-input>
                </div>
                <div>
                    <x-input name="uf" x-model="form.uf" readonly>
                      UF
                    </x-input>
                </div>
            </div>

            <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                <x-btn-primary type="submit">
                    <span x-show="loading">Salvando...</span>
                    <span x-show="!loading">Salvar Alterações</span>
                </x-btn-primary>
            </div>
        </form>
    </div>
</div>

<script>
    // A função agora recebe apenas os DADOS DO ENDEREÇO
    function addressEditor(addressInitialData) {
        return {
            loading: false,
            loadingCep: false,
            // CORREÇÃO: Inicializa com base nos dados do endereço
            form: {
                cep: addressInitialData.cep || '',
                rua: addressInitialData.rua || '',
                numero: addressInitialData.numero || '',
                bairro: addressInitialData.bairro || '',
                cidade: addressInitialData.cidade || '',
                uf: addressInitialData.uf || ''
            },

            init() {
                // Inicia a máscara no campo de CEP
                if (this.$refs.cepInput) {
                    IMask(this.$refs.cepInput, { mask: '00000-000' });
                }
            },

            async buscaCep() {
                // Remove caracteres não numéricos
                let cleanCep = this.form.cep.replace(/\D/g, '');
                
                if (cleanCep.length === 8) {
                    this.loadingCep = true;
                    try {
                        let res = await fetch(`https://viacep.com.br/ws/${cleanCep}/json/`);
                        let data = await res.json();
                        
                        if (!data.erro) {
                            this.form.rua = data.logradouro;
                            this.form.bairro = data.bairro;
                            this.form.cidade = data.localidade;
                            this.form.uf = data.uf;
                        } else {
                            alert('CEP não encontrado.');
                        }
                    } catch(e){ 
                        console.error(e);
                    } finally { 
                        this.loadingCep = false; 
                    }
                }
            },

            async submitForm() {
                this.loading = true;
                const formData = new FormData();
                const token = document.querySelector('meta[name="csrf-token"]').content;
                
                formData.append('_token', token);
                formData.append('_method', 'PATCH'); // Rota update usa PATCH

                // Adiciona campos ao FormData
                Object.keys(this.form).forEach(key => {
                    formData.append(key, this.form[key]);
                });

                try {
                    const res = await fetch("{{ route('workers.update.address') }}", {
                        method: 'POST',
                        headers: { 'Accept': 'application/json' },
                        body: formData
                    });

                    if (res.ok) {
                        window.location.reload(); 
                    } else {
                        // Se falhar, use o console (F12) para ver o erro debug_error do controller
                        alert('Erro ao salvar endereço.');
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