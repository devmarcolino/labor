<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Labor for workers</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="shortcut icon" href="../img/lb-blue.svg" type="image/x-icon">
</head>
<x-flash-manager />

<body x-data="{ openModal: false }" class="bg-gray-50 dark:bg-gray-900 transition-colors duration-500 h-screen flex flex-col">
    
    <x-loading/>

    <header class="flex justify-center w-full mx-auto pt-4 mb-2">
        <div class="flex items-center justify-between w-full max-w-2xl px-5 relative">
            <a href="{{ route('workers.account') }}" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-800 transition-colors">
                <svg class="w-6 h-6 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            
            <h1 class="text-xl font-bold text-center dark:text-white">Minhas habilidades</h1>
        </div>
    </header>

    <div class="px-5 pt-6 max-w-2xl mx-auto flex flex-col gap-6 w-full">
        
        <div class="bg-white dark:bg-gray-800 rounded-[40px] p-6 shadow-labor border border-gray-100 dark:border-gray-700">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 px-1">Competências Atuais</h3>
            
            @if($mySkills->count() > 0)
                <div class="flex flex-wrap gap-3">
                    @foreach($mySkills as $skill)
                        <div id="skill-tag-{{ $skill->id }}" class="pl-4 pr-2 py-2 rounded-full bg-sky-100 text-sky-700 font-semibold text-sm border border-sky-200 dark:bg-sky-900/30 dark:text-sky-300 dark:border-sky-800 flex items-center justify-between gap-3 group transition-all hover:border-sky-300">
                            
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                {{ $skill->nomeHabilidade ?? $skill->nomeHabilidade }}
                            </div>

                            <button onclick="removeSkill({{ $skill->id }})" 
                                    class="p-1 rounded-full hover:bg-white/50 text-sky-400 hover:text-red-500 transition-colors focus:outline-none"
                                    title="Remover habilidade">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-400">
                    <p>Você ainda não selecionou nenhuma habilidade.</p>
                </div>
            @endif

            <div class="mt-8">
                <x-btn-primary @click="openModal = true" class="w-full justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Adicionar Nova Habilidade
                </x-btn-primary>
            </div>
        </div>

        <p class="text-xs text-gray-400 text-center px-4">
            Ao adicionar novas habilidades, você precisará responder a um breve questionário para validar seu perfil.
        </p>

    </div>

    <div x-show="openModal" 
     x-cloak  
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="relative z-50">
    @include('partials.skills-edit-modal', [
        'allSkills' => $allSkills,
        'mySkillsIds' => $mySkillsIds  // <--- Importante passar essa variável nova aqui
    ])
</div>

    <script>
        async function removeSkill(id) {
            if(!confirm('Deseja realmente remover esta habilidade do seu perfil?')) return;

            const element = document.getElementById(`skill-tag-${id}`);
            // Efeito visual imediato (opacidade)
            if(element) element.style.opacity = '0.5';

            try {
                const response = await fetch(`/workers/skills/${id}/remove`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    // Remove do HTML
                    if(element) element.remove();
                    
                    // Notificação (se você usar SweetAlert ou Dispatch do Alpine)
                    window.dispatchEvent(new CustomEvent('notify', { detail: { type: 'success', title: 'Removido', msg: 'Habilidade removida.' } }));
                    
                    // Opcional: Recarregar se ficar vazio para mostrar a msg de "nenhuma habilidade"
                    if(document.querySelectorAll('[id^="skill-tag-"]').length === 0) {
                        location.reload(); 
                    }
                } else {
                    throw new Error('Falha ao remover');
                }
            } catch (error) {
                if(element) element.style.opacity = '1';
                alert('Erro ao remover habilidade.');
            }
        }
    </script>
</body>
</html>