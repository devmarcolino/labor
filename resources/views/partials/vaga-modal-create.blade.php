<div 
    x-show="openVagaModal"
    x-cloak
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-70 backdrop-blur-sm p-2"
>
    <div class="relative w-full max-w-sm rounded-3xl bg-white pt-6 pb-4 px-4 shadow-labor dark:bg-gray-800 flex flex-col max-h-[95vh] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
        <button 
            @click="openVagaModal = false"
            class="absolute top-2 right-2 text-gray-500 hover:text-gray-800 text-2xl leading-none"
            aria-label="Fechar modal"
        >&times;</button>

        <div class="text-center mb-4">
            <h2 class="text-xl font-bold text-sky-600 dark:text-sky-400">Criar nova vaga</h2>
        </div>

        <form method="POST" action="{{ route('enterprises.vagas.store') }}" enctype="multipart/form-data" class="flex flex-col gap-4">
            @csrf
            <div>
                <label class="block text-gray-700 dark:text-gray-200 font-semibold mb-1 text-sm">Tipo da vaga</label>
                <input type="text" name="tipoVaga" class="input-labor border border-gray-300 rounded-xl px-3 py-3 text-base focus:outline-none focus:ring-2 focus:ring-sky-500 w-full" required>
            </div>
            <div>
                <label class="block text-gray-700 dark:text-gray-200 font-semibold mb-1 text-sm">Função</label>
                <select name="funcVaga" class="input-labor border border-gray-300 rounded-xl px-3 py-3 text-base focus:outline-none focus:ring-2 focus:ring-sky-500 w-full" required>
                    <option value="">Selecione uma habilidade</option>
                    @foreach($skills as $skill)
                        <option value="{{ $skill->id }}">{{ $skill->nomeHabilidade }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-gray-700 dark:text-gray-200 font-semibold mb-1 text-sm">Descrição</label>
                <textarea name="descVaga" class="input-labor border border-gray-300 rounded-xl px-3 py-3 text-base focus:outline-none focus:ring-2 focus:ring-sky-500 w-full" required></textarea>
            </div>
            <div>
                <label class="block text-gray-700 dark:text-gray-200 font-semibold mb-1 text-sm">Valor</label>
                <input type="number" step="0.01" name="valor_vaga" class="input-labor border border-gray-300 rounded-xl px-3 py-3 text-base focus:outline-none focus:ring-2 focus:ring-sky-500 w-full" required>
            </div>
            <div>
                <label class="block text-gray-700 dark:text-gray-200 font-semibold mb-1 text-sm">Data</label>
                <input type="date" name="dataVaga" class="input-labor border border-gray-300 rounded-xl px-3 py-3 text-base focus:outline-none focus:ring-2 focus:ring-sky-500 w-full" required>
            </div>
            <div>
                <label class="block text-gray-700 dark:text-gray-200 font-semibold mb-1 text-sm">Imagem (opcional)</label>
                <input type="file" name="imgVaga" accept="image/*" class="input-labor border border-gray-300 rounded-xl px-3 py-3 text-base focus:outline-none focus:ring-2 focus:ring-sky-500 w-full">
            </div>
            <div class="flex flex-col gap-2 mt-4">
                <button type="button" @click="openVagaModal = false" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-3 px-6 rounded-full w-full">Cancelar</button>
                <button type="submit" class="btn-labor bg-sky-600 hover:bg-sky-700 text-white font-bold py-3 px-6 rounded-full w-full shadow-labor transition-all duration-200">Criar vaga</button>
            </div>
        </form>
    </div>
</div>
