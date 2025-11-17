<div aria-live="assertive" class="pointer-events-none fixed inset-0 flex items-end px-4 py-6 sm:items-start sm:p-6 z-[100]">
    <div class="flex w-full flex-col items-center space-y-4 sm:items-end">
        
        {{-- 1. SUCESSO (session('success')) --}}
        @if (session()->has('success'))
            <x-toast type="success" title="Sucesso!" msg="{{ session('success') }}" />
        @endif

        {{-- 2. STATUS (session('status')) --}}
        @if (session()->has('status'))
            <x-toast type="info" title="Informação" msg="{{ session('status') }}" />
        @endif

        {{-- 3. ERRO (session('error')) --}}
        @if (session()->has('error'))
            <x-toast type="danger" title="Erro!" msg="{{ session('error') }}" />
        @endif

        {{-- 4. AVISO (session('warning')) --}}
        @if (session()->has('warning'))
            <x-toast type="warning" title="Atenção" msg="{{ session('warning') }}" />
        @endif

        {{-- 5. ERROS DE VALIDAÇÃO ($errors) --}}
        {{-- Se houver erros de validação (como no cadastro), mostra uma lista --}}
        @if ($errors->any())
            <x-toast type="danger" title="Encontramos alguns problemas:">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-toast>
        @endif

    </div>
</div>