<div x-data="{ 
        messages: [],
        add(data) {
            this.messages.push({
                id: Date.now(),
                type: data.type || 'info',
                title: data.title || '',
                msg: data.msg || '',
                show: true
            });
            // Fecha sozinho em 4 segundos
            setTimeout(() => { this.remove(this.messages.length - 1) }, 5000);
        },
        remove(index) {
            if(this.messages[index]) this.messages[index].show = false;
        }
     }"
     @notify.window="add($event.detail)"
     class="fixed inset-0 flex flex-col items-center sm:items-end px-4 py-6 pointer-events-none z-[100] space-y-4">
    
    {{-- MENSAGENS DO PHP (SESSÃO) --}}
    @if (session()->has('success'))
        <x-toast type="success" title="Sucesso" msg="{{ session('success') }}" />
    @endif
    @if (session()->has('error'))
        <x-toast type="danger" title="Erro" msg="{{ session('error') }}" />
    @endif

    {{-- MENSAGENS DO JAVASCRIPT (ALPINE) --}}
    <template x-for="(toast, index) in messages" :key="toast.id">
        <div x-show="toast.show"
             x-transition:enter="transform ease-out duration-300 transition"
             x-transition:enter-start="-translate-y-2 opacity-0"
             x-transition:enter-end="translate-y-0 opacity-100"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="-translate-y-2 opacity-0"
             class="pointer-events-auto w-full max-w-sm bg-white dark:bg-gray-800 rounded-lg shadow-lg border-l-4 overflow-hidden"
             :class="{
                'border-green-500': toast.type === 'success',
                'border-red-500': toast.type === 'danger',
                'border-yellow-500': toast.type === 'warning',
                'border-blue-500': toast.type === 'info'
             }">
             
            <div class="p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <template x-if="toast.type === 'danger'">
                            <svg class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </template>
                        <template x-if="toast.type === 'success'">
                            <svg class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </template>
                    </div>

                    <div class="ml-3 w-0 flex-1 pt-0.5">
                        <p class="text-sm font-bold text-gray-900 dark:text-white" x-text="toast.title"></p>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400" x-text="toast.msg"></p>
                    </div>

                    <div class="ml-4 flex flex-shrink-0">
                        <button @click="toast.show = false" class="inline-flex text-gray-400 hover:text-gray-500 focus:outline-none">
                            <span class="sr-only">Fechar</span>
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>