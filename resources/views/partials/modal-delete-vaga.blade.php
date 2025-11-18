<div x-data="{ open: false, vagaId: null }" x-show="open" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-90 backdrop-blur-sm p-4">
    <div class="relative w-full max-w-md rounded-[40px] bg-white pt-10 pb-6 px-6 shadow-2xl flex flex-col items-center">
        <svg class="w-12 h-12 text-red-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
        <h2 class="text-xl font-bold text-gray-900 mb-2">Confirmar exclusão</h2>
        <p class="text-gray-500 mb-6 text-center">Tem certeza que deseja deletar esta vaga? Esta ação não poderá ser desfeita.</p>
        <div class="flex gap-2 w-full">
            <button @click="open = false" class="flex-1 bg-gray-200 text-gray-700 font-semibold py-2 rounded-full">Cancelar</button>
            <form :action="'/enterprises/vagas/delete/' + vagaId" method="POST" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full bg-red-500 text-white font-semibold py-2 rounded-full">Confirmar exclusão</button>
            </form>
        </div>
    </div>
</div>
