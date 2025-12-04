
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Labor for enterprises</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <link rel="shortcut icon" href="../img/lb-blue.svg" type="image/x-icon">
</head>
@include('partials.vaga-candidatos-modal')
<x-flash-manager />

<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-500 h-screen flex flex-col">

    <x-loading />

    <header class="flex items-center justify-between py-3 px-5">

        <button class="icon-btn">
            <a href="{{ url('enterprises/chat') }}" class="text-gray-500 dark:text-gray-400">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5"
                    stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d='M11.953 2.25c-2.317 0-4.118 0-5.52.15-1.418.153-2.541.47-3.437 1.186-.92.736-1.35 1.693-1.553 2.9-.193 1.152-.193 2.618-.193 4.446v.183c0 1.782 0 3.015.2 3.934.108.495.278.925.545 1.323.264.392.6.722 1.001 1.042.631.505 1.375.81 2.254 1V21a.75.75 0 0 0 1.123.65c.586-.335 1.105-.7 1.58-1.044l.304-.221a22 22 0 0 1 1.036-.73c.844-.548 1.65-.905 2.707-.905h.047c2.317 0 4.118 0 5.52-.15 1.418-.153 2.541-.47 3.437-1.186.4-.32.737-.65 1-1.042.268-.398.438-.828.546-1.323.2-.919.2-2.152.2-3.934v-.183c0-1.828 0-3.294-.193-4.445-.203-1.208-.633-2.165-1.553-2.901-.896-.717-2.019-1.033-3.437-1.185-1.402-.151-3.203-.151-5.52-.151z' />
                </svg>
            </a>
        </button>

        <ul class="flex items-center py-1.5 px-2.5 bg-gray-100 dark:bg-gray-800 rounded-full shadow-sm" id="icon-tabs" data-tabs-toggle="#icon-tabs-content" role="tablist">
            <li role="presentation">
                <a href="#"
                    class="group flex px-5 py-2 rounded-full text-gray-500 dark:text-gray-300 aria-selected:bg-white aria-selected:dark:bg-gray-950/85 aria-selected:text-sky-600 aria-selected:shadow-sm transition-all ease-linear duration-200"
                    id="flame-tab" data-tabs-target="#flame" role="tab" aria-selected="true">

                    <div class="group-aria-selected:hidden">
                        <svg width="24" height="24" fill="none" stroke="currentColor">
                            <path d="M8.5 14.5C9.16304 14.5 9.79893 14.2366 10.2678 13.7678C10.7366 13.2989 11 12.663 11 12C11 10.62 10.5 10 10 9C8.928 6.857 9.776 4.946 12 3C12.5 5.5 14 7.9 16 9.5C18 11.1 19 13 19 15C19 15.9193 18.8189 16.8295 18.4672 17.6788C18.1154 18.5281 17.5998 19.2997 16.9497 19.9497C16.2997 20.5998 15.5281 21.1154 14.6788 21.4672C13.8295 21.8189 12.9193 22 12 22C11.0807 22 10.1705 21.8189 9.32122 21.4672C8.47194 21.1154 7.70026 20.5998 7.05025 19.9497C6.40024 19.2997 5.88463 18.5281 5.53284 17.6788C5.18106 16.8295 5 15.9193 5 15C5 13.847 5.433 12.706 6 12C6 12.663 6.26339 13.2989 6.73223 13.7678C7.20107 14.2366 7.83696 14.5 8.5 14.5Z" />
                        </svg>
                    </div>

                    <div class="hidden group-aria-selected:block">
                        <svg width="24" height="24" fill="#0284C7">
                            <path d="M8.5 14.5C9.16304 14.5 9.79893 14.2366 10.2678 13.7678C10.7366 13.2989 11 12.663 11 12C11 10.62 10.5 10 10 9C8.928 6.857 9.776 4.946 12 3C12.5 5.5 14 7.9 16 9.5C18 11.1 19 13 19 15C19 15.9193 18.8189 16.8295 18.4672 17.6788C18.1154 18.5281 17.5998 19.2997 16.9497 19.9497C16.2997 20.5998 15.5281 21.1154 14.6788 21.4672C13.8295 21.8189 12.9193 22 12 22C11.0807 22 10.1705 21.8189 9.32122 21.4672C8.47194 21.1154 7.70026 20.5998 7.05025 19.9497C6.40024 19.2997 5.88463 18.5281 5.53284 17.6788C5.18106 16.8295 5 15.9193 5 15C5 13.847 5.433 12.706 6 12C6 12.663 6.26339 13.2989 6.73223 13.7678C7.20107 14.2366 7.83696 14.5 8.5 14.5Z" />
                        </svg>
                    </div>
                </a>
            </li>

            <li role="presentation">
                <a href="#"
                    class="group flex px-5 py-2 rounded-full text-gray-500 dark:text-gray-300 aria-selected:bg-white aria-selected:dark:bg-gray-950/85 aria-selected:text-sky-600 aria-selected:shadow-sm"
                    id="home-tab" data-tabs-target="#home" role="tab" aria-selected="false">

                    <div class="group-aria-selected:hidden">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h7.5" />
                        </svg>
                    </div>

                    <div class="hidden group-aria-selected:block">
                        <svg class="w-6 h-6" fill="#0284C7">
                            <path d="M11.47 3.841a.75.75 0 0 1 1.06 0l8.69 8.69a.75.75 0 1 0 1.06-1.06l-8.689-8.69a2.25 2.25 0 0 0-3.182 0l-8.69 8.69a.75.75 0 1 0 1.06 1.06l8.69-8.69Z" />
                            <path d="M12 5.432 4.5 12.932V21a1.5 1.5 0 0 0 1.5 1.5h4.5V18A1.5 1.5 0 0 1 12 16.5h.008A1.5 1.5 0 0 1 13.5 18v4.5h4.5A1.5 1.5 0 0 0 19.5 21V12.932L12 5.432Z" />
                        </svg>
                    </div>
                </a>
            </li>
        </ul>

        <a href="{{ route('enterprises.account') }}" class="text-gray-600">
            <div class="bg-gray-200 bg-center bg-cover w-[40px] h-[40px] rounded-full shadow-md overflow-hidden"
                style="background-image: url('{{ Auth::user()->fotoEmpresa ? asset('storage/' . Auth::user()->fotoEmpresa) : '' }}');">

                @if(!Auth::user()->fotoEmpresa)
                <svg class="w-6 h-6 text-gray-400" fill="currentColor">
                    <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                @endif
            </div>
        </a>
    </header>

    <main id="icon-tabs-content" class="relative p-3 flex-1 h-full">

        <div id="flame" class="h-full flex flex-col items-center justify-center">
                <div x-data="enterpriseFeed()" x-init="init()" class="w-full h-full relative bg-gray-50 dark:bg-gray-900">

        <div x-show="isLoading" class="flex flex-col items-center justify-center h-full absolute inset-0 z-0">
            <svg class="animate-spin h-10 w-10 text-sky-600 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            <p class="text-gray-500 font-medium">Analisando candidatos...</p>
        </div>

        <div x-show="!isLoading && cards.length === 0" class="flex flex-col items-center justify-center h-full text-center p-6 absolute inset-0 z-0">
            <div class="w-24 h-24 bg-gray-200 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <h3 class="text-xl font-bold text-gray-800 dark:text-white">Tudo limpo!</h3>
            <p class="text-gray-500 mt-2">Você zerou as pendências das suas vagas.</p>
        </div>

        <template x-for="(card, index) in cards" :key="card.id">
            <div class="absolute inset-0 flex justify-center items-center p-4 pointer-events-none"
                :style="`z-index: ${cards.length - index};`">
                
                <div class="relative w-full h-full max-w-md bg-black rounded-[40px] overflow-hidden shadow-2xl card-item pointer-events-auto select-none"
                    :data-vaga-id="card.id"
                    :data-user-id="card.candidato.id">
                    
                    <img :src="card.candidato.foto" class="absolute inset-0 w-full h-full object-cover opacity-90">
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/40 to-transparent"></div>

                    <div class="absolute top-4 left-0 right-0 flex justify-center z-20">

                    <button class="absolute justify-between right-4 left-4 max-w-2xl flex items-center gap-3 bg-gray-900/20 backdrop-blur-md border border-white/10 rounded-full px-6 py-3 shadow-lg" @click="window.dispatchEvent(
        new CustomEvent('open-candidates-modal', { detail: { id: card.id } })
    )">
                        <div class="flex gap-3">
                            <div class="rounded-full">
                                <img src="../img/ia.svg" alt="" class="w-6 h-6">
                            </div>

                            <div>
                                <p class="text-xs font-bold text-gray-100 uppercase tracking-wider">Melhor Candidato</p>
                                <p class="text-[10px] text-gray-200 font-medium">Analisado por Labor IA ©</p>
                            </div>
                        </div>

                        <div class="text-white/50">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </button>
                    </div>

                    <div class="absolute bottom-0 w-full p-8 pb-10 text-white z-10">
                        
                        <div class="mb-2 flex items-center gap-2">
                            <span class="px-3 py-1 rounded-full bg-sky-600/80 backdrop-blur text-xs font-bold border border-sky-400/30" x-text="card.titulo_vaga"></span>
                        </div>

                        <h2 class="text-4xl font-bold leading-none mb-1">
                            <span x-text="card.candidato.nome"></span>
                            <span class="text-2xl font-normal opacity-70" x-text="card.candidato.idade"></span>
                        </h2>
                        
                        <p class="text-gray-300 text-sm flex items-center gap-1 mb-6">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span x-text="card.candidato.cidade"></span>
                            <span x-show="card.total_candidatos > 1" class="ml-2 text-sky-400 text-xs font-bold" x-text="'+' + (card.total_candidatos - 1) + ' outros'"></span>
                        </p>
                    </div>
                </div>
            </div>
            @include('partials.vaga-candidatos-modal')

        </template>

    </div>

        </div>

        <div id="home" class="hidden p-2 mx-auto" role="tabpanel">
            <div class="flex flex-col mx-auto items-center max-w-2xl gap-6">

                <button class="bg-transparent w-full text-left">
                    <a href="{{ url('enterprises/schedule') }}">
                        <div class="bg-white dark:bg-gray-800 shadow-labor hover:shadow-xl ease-in border-btn flex justify-between items-center py-6 px-8">
                            <div class="flex gap-5 items-center">
                                <svg class="text-gray-900 dark:text-gray-200" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M21 7.5V6C21 5.46957 20.7893 4.96086 20.4142 4.58579C20.0391 4.21071 19.5304 4 19 4H5C4.46957 4 3.96086 4.21071 3.58579 4.58579C3.21071 4.96086 3 5.46957 3 6V20C3 20.5304 3.21071 21.0391 3.58579 21.4142C3.96086 21.7893 4.46957 22 5 22H8.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M16 2V6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M8 2V6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M3 10H8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M17.5 17.5L16 16.25V14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M22 16C22 17.5913 21.3679 19.1174 20.2426 20.2426C19.1174 21.3679 17.5913 22 16 22C14.4087 22 12.8826 21.3679 11.7574 20.2426C10.6321 19.1174 10 17.5913 10 16C10 14.4087 10.6321 12.8826 11.7574 11.7574C12.8826 10.6321 14.4087 10 16 10C17.5913 10 19.1174 10.6321 20.2426 11.7574C21.3679 12.8826 22 14.4087 22 16Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>

                                <div class="flex flex-col">
                                    <h1 class="text-xl text-gray-900 dark:text-gray-200">Escala <span class="font-bold text-sky-600 dark:text-sky-500">Automática</span></h1>
                                    <p class="text-md text-gray-400">Abrir escala</p>
                                </div>
                            </div>
                            <svg class="text-gray-900 dark:text-gray-200" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 5L19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                        </div>
                    </a>
                </button>

                <button class="bg-transparent w-full text-left flex flex-col gap-4">
                    <a href="{{ route('enterprises.vagas.list') }}">
                        <div class="bg-white dark:bg-gray-800 flex flex-col gap-3 shadow-labor hover:shadow-xl ease-in rounded-[45px] justify-center items-center px-4 py-4">
                            <div class="flex items-center w-full justify-between px-4 py-4">
                                <div class="flex gap-5 items-center">
                                    <svg class="text-gray-900 dark:text-gray-200" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
                                    <path d='M19 7h-7.34a2 2 0 0 1-1.322-.5l-2.272-2M19 7a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h1.745a2 2 0 0 1 1.322.5M19 7a2.5 2.5 0 0 0-2.5-2.5H8.066'/>
                                    </svg>

                                    <div class="flex flex-col">
                                        <h1 class="text-xl text-gray-900 dark:text-gray-200">Minhas <span class="font-bold text-sky-600 dark:text-sky-500">Vagas</span></h1>
                                        <p class="text-md text-gray-400">Ver vagas postadas por mim</p>
                                    </div>
                                </div>
                                <svg class="text-gray-900 dark:text-gray-200" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 5L19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                        

                        <div class="w-full flex justify-center mb-2">
                            <div class="w-1/3 h-[2px] bg-gray-100 dark:bg-gray-700 rounded-full"></div>
                        </div>

                        @php
                        $ultimaVaga = \App\Http\Controllers\EnterpriseVagaController::ultimaVaga(Auth::guard('empresa')->id());
                        $empresaId = Auth::guard('empresa')->id();
                        $totalVisualizacoes = \DB::table('vagas_tb')
                            ->where('idEmpresa', $empresaId)
                            ->join('visualizacao_vaga', 'vagas_tb.id', '=', 'visualizacao_vaga.idVaga')
                            ->count();
                        @endphp

                        <div class="relative bg-white dark:bg-gray-800 justify-center rounded-[30px] w-full flex flex-col gap-2 shadow-lg text-center p-4 border border-gray-100 dark:border-gray-700 overflow-hidden group transition-all">
    
    @if($ultimaVaga)
        @php 
            // Pega a imagem da vaga ou uma padrão
            $imgVaga = $ultimaVaga->imgVaga ?? null; 
            $bgImage = $imgVaga 
                ? asset('storage/' . $imgVaga) 
                : asset('img/default-job-bg.jpg'); 
        @endphp

        <div class="absolute inset-0 z-0 pointer-events-none">
            <img src="{{ $bgImage }}" 
                 class="w-full h-full object-cover filter blur-[4px] scale-110 opacity-30 dark:opacity-20 transition-transform duration-700 group-hover:scale-125" 
                 alt="">
            
            <div class="absolute inset-0 bg-gradient-to-r from-white via-white/70 to-white/40 dark:from-gray-800 dark:via-gray-800/70 dark:to-gray-800/40"></div>
        </div>

        <div class="relative z-10 flex flex-col items-center">
            
            <div class="flex items-center gap-2 mb-1 w-full justify-center text-center">
                <div class="p-1.5 bg-sky-50 dark:bg-sky-900/30 rounded-full">
                    <img src="/img/gauge.svg" class="w-4 h-4 text-sky-600 dark:text-sky-400">
                </div>
                <span class="text-xs text-gray-600 dark:text-gray-300 font-medium">
                    Última vaga postada: ({{ $ultimaVaga->created_at->diffForHumans() }})
                </span>
            </div>

            <div class="font-bold text-lg text-gray-900 dark:text-white mb-2 drop-shadow-sm">
               {{ $ultimaVaga->skill->nomeHabilidade ?? $ultimaVaga->funcVaga }} 
               <span class="text-sm font-normal text-gray-500 dark:text-gray-400">({{ $ultimaVaga->dataVaga->format('d/m/Y') }})</span>
            </div>

            <div class="w-full flex justify-center mb-3">
                <div class="w-1/3 h-[2px] bg-gray-200/60 dark:bg-gray-700/60 rounded-full"></div>
            </div>

            <div class="gap-2 w-full justify-center text-center flex flex-col items-center">
                
                <div class="flex gap-2 items-center text-gray-500 dark:text-gray-400 bg-white/50 dark:bg-gray-800/50 px-3 py-1.5 rounded-full backdrop-blur-sm">
                    <img src="/img/eye.svg" class="w-4 h-4 opacity-70">
                    <span class="text-sm font-bold text-sky-700 dark:text-sky-400">{{ $totalVisualizacoes }}</span>
                    <span class="text-xs">visualizações</span>
                </div>

                <div class="flex items-center gap-2 text-gray-500 dark:text-gray-400 bg-white/50 dark:bg-gray-800/50 px-3 py-1.5 rounded-full backdrop-blur-sm">
                    <img src="/img/heart-handshake.svg" class="w-4 h-4 opacity-70">
                    <span class="text-sm font-bold text-sky-700 dark:text-sky-400">{{ $ultimaVaga->candidaturas()->count() }}</span>
                    <span class="text-xs">candidaturas</span>
                </div>
            </div>
        </div>

    @else
        <div class="relative z-10 flex flex-col items-center justify-center py-6 text-gray-500 dark:text-gray-400">
            <svg class="w-12 h-12 mb-3 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
            <p>Nenhuma vaga cadastrada.</p>
        </div>
    @endif
</div>
                        </div>
                    </a>
                    
                    
                </button>
            </div>               
            </div>
        </div>
    </main>

    @auth('empresa')
        @if(Auth::guard('empresa')->user()->status == 1)
            @include('partials.onboarding-modal-enterprise')
        @endif
    @endauth

</body>
</html>
