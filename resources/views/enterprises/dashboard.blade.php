<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Labor for enterprises</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <link rel="shortcut icon" href="../img/lb-blue.svg" type="image/x-icon">
</head>

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
            @php
                $empresaId = Auth::guard('empresa')->id();
                $vagas = \App\Models\Vaga::where('idEmpresa', $empresaId)->latest('created_at')->get();
            @endphp

            @foreach($vagas as $vaga)
                @php
                    $melhorCandidatura = $vaga->candidaturas->sortByDesc('nota_ia')->first();
                    $user = $melhorCandidatura ? $melhorCandidatura->user : null;
                @endphp

                {{-- CARD NOVO DO MELHOR CANDIDATO --}}
                @if($user)
                <div class="w-full max-w-md rounded-3xl overflow-hidden shadow-lg relative bg-black mb-7">

                    {{-- FOTO DE FUNDO --}}
                    <div class="w-full h-80 relative">
                        <img 
                            src="{{ $user->fotoUser ? asset('storage/' . $user->fotoUser) : asset('img/default-user.png') }}"
                            class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-b from-black/10 via-black/40 to-black"></div>
                    </div>

                    {{-- TAG MELHOR CANDIDATO --}}
                    <div class="absolute top-3 left-3 right-3 flex items-center justify-between bg-white/90 dark:bg-gray-900/80 backdrop-blur px-3 py-2 rounded-xl shadow">
                        <div class="flex items-center gap-2">
                            <img 
                                src="{{ $user->fotoUser ? asset('storage/' . $user->fotoUser) : asset('img/default-user.png') }}"
                                class="w-8 h-8 rounded-full object-cover ring-1 ring-gray-200">
                            <div class="flex flex-col leading-tight">
                                <span class="text-[11px] font-bold text-gray-900 dark:text-white">Melhor candidato</span>
                                <span class="text-[10px] text-gray-500">Analisado por Labor IA 🤖</span>
                            </div>
                        </div>
                        <img src="/img/lb-blue.svg" class="w-7 h-7">
                    </div>

                    {{-- RODAPÉ --}}
                    <div class="absolute bottom-0 w-full px-4 pb-4">
                        <h2 class="text-lg font-bold text-white">
                            {{ $user->username }}
                        </h2>

                        <p class="text-sm text-gray-200">
                            {{ $vaga->funcVaga }}
                        </p>

                        <div class="mt-2 flex items-center gap-1 bg-black/60 px-3 py-2 rounded-xl w-fit">
                            <img src="/img/star.svg" class="w-4 h-4" alt="">
                            <span class="text-white text-sm font-semibold">
                                {{ number_format($melhorCandidatura->nota_ia ?? 4.0, 1) }}
                            </span>
                        </div>
                    </div>

                </div>
                @else
                <div class="text-gray-400">Nenhum candidato disponível</div>
                @endif

            @endforeach

        </div>

        <div id="home" class="hidden p-2 mx-auto" role="tabpanel">
            <div class="flex flex-col mx-auto items-center max-w-2xl gap-6">

                <button class="bg-transparent w-full text-left">
                    <a href="{{ url('enterprises/schedule') }}">
                        <div class="bg-white dark:bg-gray-800 shadow-labor border-btn flex justify-between items-center py-6 px-8">
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
                        <div class="bg-white dark:bg-gray-800 flex flex-col gap-3 shadow-labor rounded-[45px] justify-center items-center px-4 py-4">
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

                        <div class="bg-white dark:bg-gray-800 justify-center rounded-[25px] w-full flex flex-col gap-2 shadow-lg text-center p-4 border border-gray-100 dark:border-gray-700">
                            @if($ultimaVaga)
                                <div class="flex items-center gap-2 mb-1 w-full justify-center text-center">
                                    <img src="/img/gauge.svg" class="w-4 h-4">
                                    <span class="text-xs text-gray-500 dark:text-gray-300">
                                        Última vaga postada: ({{ $ultimaVaga->created_at->diffForHumans() }})
                                    </span>
                                </div>

                                <div class="font-bold text-md text-gray-900 dark:text-white mb-1">
                                   {{ $ultimaVaga->skill->nomeHabilidade ?? $ultimaVaga->funcVaga }} ({{ $ultimaVaga->dataVaga->format('d/m/Y') }})
                                </div>

                                <div class="w-full flex justify-center mb-2">
                            <div class="w-1/3 h-[2px] bg-gray-100 dark:bg-gray-700 rounded-full"></div>
                        </div>

                        <div class="gap-1 mt-2 w-full justify-center text-center flex flex-col items-center">
                            <div class="flex gap-1.5 items-center text-gray-400">
                                <img src="/img/eye.svg" class="w-4 h-4">
                                <span class="text-xs font-bold text-sky-700">{{ $totalVisualizacoes }}</span>
                                <span class="text-xs text-gray-400">visualizações</span>
                                        
                            </div>

                            <div class="flex items-center gap-1.5 text-gray-400">
                                <img src="/img/heart-handshake.svg" class="w-4 h-4">
                                <span class="text-xs font-bold text-sky-700">{{ $ultimaVaga->candidaturas()->count() }}</span>
                                <span class="text-xs text-gray-400">candidaturas         
                                </span>
                            </div>
                        </div>

                            @else
                                <div class="text-gray-500 dark:text-gray-300">Nenhuma vaga cadastrada.</div>
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
