<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Labor for workers</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <link rel="shortcut icon" href="../img/lb-blue.svg" type="image/x-icon">
</head>
<x-flash-manager />

<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-500 h-screen flex flex-col">
    
    <x-loading/>

    <header class="flex items-center justify-between py-3 px-5">

        <button class="icon-btn">
            <a href="{{ url('workers/chat') }}" class="text-gray-500 dark:text-gray-400">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d='M11.953 2.25c-2.317 0-4.118 0-5.52.15-1.418.153-2.541.47-3.437 1.186-.92.736-1.35 1.693-1.553 2.9-.193 1.152-.193 2.618-.193 4.446v.183c0 1.782 0 3.015.2 3.934.108.495.278.925.545 1.323.264.392.6.722 1.001 1.042.631.505 1.375.81 2.254 1V21a.75.75 0 0 0 1.123.65c.586-.335 1.105-.7 1.58-1.044l.304-.221a22 22 0 0 1 1.036-.73c.844-.548 1.65-.905 2.707-.905h.047c2.317 0 4.118 0 5.52-.15 1.418-.153 2.541-.47 3.437-1.186.4-.32.737-.65 1-1.042.268-.398.438-.828.546-1.323.2-.919.2-2.152.2-3.934v-.183c0-1.828 0-3.294-.193-4.445-.203-1.208-.633-2.165-1.553-2.901-.896-.717-2.019-1.033-3.437-1.185-1.402-.151-3.203-.151-5.52-.151z'/></svg>
            </a>
        </button>

        <ul class="flex items-center py-1.5 px-2.5 bg-gray-100 dark:bg-gray-800 rounded-full shadow-sm" id="icon-tabs" data-tabs-toggle="#icon-tabs-content" role="tablist">
            <li role="presentation">
                <a href="#" 
                class="group flex px-5 py-2 rounded-full text-gray-500 dark:text-gray-300 aria-selected:bg-white aria-selected:dark:bg-gray-950/85 aria-selected:text-sky-600 aria-selected:shadow-sm transition-all ease-linear duration-200"
                id="flame-tab" data-tabs-target="#flame" type="button" role="tab" aria-controls="flame" aria-selected="true">
                    
                    <div class="group-aria-selected:hidden">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8.5 14.5C9.16304 14.5 9.79893 14.2366 10.2678 13.7678C10.7366 13.2989 11 12.663 11 12C11 10.62 10.5 10 10 9C8.928 6.857 9.776 4.946 12 3C12.5 5.5 14 7.9 16 9.5C18 11.1 19 13 19 15C19 15.9193 18.8189 16.8295 18.4672 17.6788C18.1154 18.5281 17.5998 19.2997 16.9497 19.9497C16.2997 20.5998 15.5281 21.1154 14.6788 21.4672C13.8295 21.8189 12.9193 22 12 22C11.0807 22 10.1705 21.8189 9.32122 21.4672C8.47194 21.1154 7.70026 20.5998 7.05025 19.9497C6.40024 19.2997 5.88463 18.5281 5.53284 17.6788C5.18106 16.8295 5 15.9193 5 15C5 13.847 5.433 12.706 6 12C6 12.663 6.26339 13.2989 6.73223 13.7678C7.20107 14.2366 7.83696 14.5 8.5 14.5Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>

                    <div class="hidden group-aria-selected:block">
                    <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8.5 14.5C9.16304 14.5 9.79893 14.2366 10.2678 13.7678C10.7366 13.2989 11 12.663 11 12C11 10.62 10.5 10 10 9C8.928 6.857 9.776 4.946 12 3C12.5 5.5 14 7.9 16 9.5C18 11.1 19 13 19 15C19 15.9193 18.8189 16.8295 18.4672 17.6788C18.1154 18.5281 17.5998 19.2997 16.9497 19.9497C16.2997 20.5998 15.5281 21.1154 14.6788 21.4672C13.8295 21.8189 12.9193 22 12 22C11.0807 22 10.1705 21.8189 9.32122 21.4672C8.47194 21.1154 7.70026 20.5998 7.05025 19.9497C6.40024 19.2997 5.88463 18.5281 5.53284 17.6788C5.18106 16.8295 5 15.9193 5 15C5 13.847 5.433 12.706 6 12C6 12.663 6.26339 13.2989 6.73223 13.7678C7.20107 14.2366 7.83696 14.5 8.5 14.5Z"  fill="#0284C7"/>
                        </svg>
                    </div>
                </a>
            </li>
            <li role="presentation">
                <a href="#" 
                class="group flex px-5 py-2 rounded-full text-gray-500 dark:text-gray-300 aria-selected:bg-white aria-selected:dark:bg-gray-950/85 aria-selected:text-sky-600 aria-selected:shadow-sm transition-all ease-linear duration-200" 
                id="home-tab" data-tabs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="false">
                    
                    <div class="group-aria-selected:hidden">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h7.5" />
                        </svg>
                    </div>

                    <div class="hidden group-aria-selected:block">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="#0284C7">
                            <path d="M11.47 3.841a.75.75 0 0 1 1.06 0l8.69 8.69a.75.75 0 1 0 1.06-1.06l-8.689-8.69a2.25 2.25 0 0 0-3.182 0l-8.69 8.69a.75.75 0 1 0 1.06 1.06l8.69-8.69Z" />
                            <path d="M12 5.432 4.5 12.932V21a1.5 1.5 0 0 0 1.5 1.5h4.5V18A1.5 1.5 0 0 1 12 16.5h.008A1.5 1.5 0 0 1 13.5 18v4.5h4.5A1.5 1.5 0 0 0 19.5 21V12.932L12 5.432Z" />
                        </svg>
                    </div>
                </a>
            </li>
        </ul>

            <a href="{{ route('workers.account') }}" class="text-gray-600 hover:text-gray-900 shadow-labor rounded-full">
    <div class="bg-gray-200 flex items-center justify-center w-[40px] h-[40px] rounded-full shadow-md overflow-hidden">
        @if(Auth::user()->fotoUser)
            <img src="{{ asset('storage/' . Auth::user()->fotoUser) }}" alt="Foto de perfil" class="object-cover w-full h-full rounded-full" />
        @else
            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
        @endif
    </div>
</a>
    </header>

    <main id="icon-tabs-content" class="relative p-3 flex-1 h-full">

        <div class="h-full" id="flame" x-transition role="tabpanel" aria-labelledby="flame-tab">
            <div x-data="cardStack()" x-init="initWatcher()" class="w-full h-full">

                <div x-show="isLoading" class="flex flex-col items-center justify-center h-full text-center text-gray-500 dark:text-gray-400">
                    <p class="text-lg font-semibold">Carregando vagas...</p>
                </div>

                <div x-show="error" class="flex flex-col items-center justify-center h-full text-center text-red-500">
                    <p class="text-lg font-semibold" x-text="error"></p>
                </div>

                <div x-show="!isLoading && cards.length === 0 && !error" class="flex flex-col items-center justify-center h-full text-center text-gray-500 dark:text-gray-400">
                    <div>
                        <img src="../img/empty-box.png" alt="" class="opacity-50 mx-auto" width="252px" height="165px">

                        <div class="mt-5">
                            <p class="text-2xl font-bold">É tudo por agora!</p>
                            <p>Não há mais vagas por perto no momento.</p>
                        </div>
                    </div>
                </div>

                <template x-transition x-for="(card, index) in cards" :key="card.id">
                    <div class="absolute inset-0 flex justify-center items-center p-4"
                        :style="`z-index: ${cards.length - index};`">
                        
                        <div class="relative w-full h-full max-w-md mx-auto border-match overflow-hidden shadow-2xl card-item bg-gray-200">
                            <img :src="card.image" :alt="card.title" class="absolute inset-0 h-full w-full object-cover">
                            <div class="absolute inset-0 flex flex-col justify-end bg-gradient-to-t from-black/80 to-transparent p-6 text-white">
                                <h2 class="text-3xl font-bold" x-text="card.title"></h2>
                                <p class="text-sm text-gray-200" x-text="card.desc"></p>
                                <div class="mt-4 flex items-center gap-2 w-full rounded-full bg-black/30 py-2 px-3 backdrop-blur-sm">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-200 border border-white overflow-hidden">
                                        <img :src="card.fotoEmpresa" alt="Foto da empresa" class="object-cover w-full h-full rounded-full" />
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold" x-text="card.company"></p>
                                        <p class="text-xs text-gray-200" x-text="card.ramo"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <div class="hidden p-2 mx-auto" id="home" role="tabpanel" x-transition aria-labelledby="home-tab">
            <div class="flex flex-col mx-auto items-center justify-center w-full max-w-2xl gap-3">
                <button class="bg-transparent w-full text-left">
                    <a href="{{ url('workers/schedule') }}">
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
                                    <h1 class="text-xl text-gray-900 dark:text-gray-200">Seus <span class="font-bold text-sky-600 dark:text-sky-500">trabalhos</span></h1>
                                    <p class="text-md text-gray-400">Abrir escala</p>
                                </div>
                            </div>

                            <div>
                                <svg class="text-gray-900 dark:text-gray-200" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 5L19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                        </div>
                    </a>
                </button>

                <button class="bg-transparent w-full text-left">
                    <a href="{{ route('workers.jobs') }}">
                        <div class="bg-white dark:bg-gray-800 shadow-labor border-btn flex flex-col gap-3 items-center pt-6 pb-4 px-4">
                            <div class="flex w-full justify-between items-center px-4">
                                <div class="flex gap-5 items-center">
                                    <svg class="text-gray-900 dark:text-gray-200" width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M11 19.5C15.4183 19.5 19 15.9183 19 11.5C19 7.08172 15.4183 3.5 11 3.5C6.58172 3.5 3 7.08172 3 11.5C3 15.9183 6.58172 19.5 11 19.5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M21 21.4999L16.65 17.1499" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M11 8.5V14.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M8 11.5H14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>


                                    <div class="flex flex-col">
                                        <h1 class="text-xl text-gray-900 dark:text-gray-200">Outras <span class="font-bold text-sky-600 dark:text-sky-500">vagas</span></h1>
                                        <p class="text-md text-gray-400">Mais vagas para você</p>
                                    </div>
                                </div>

                                <div>
                                    <svg class="text-gray-900 dark:text-gray-200" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 5L19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            </div>

                            <div class="flex w-full py-2 justify-center gap-3 items-center bg-gray-100 shadow-sm rounded-full dark:bg-gray-900/85">
                                <svg class="text-gray-800 dark:text-gray-300" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M20 10C20 16 12 22 12 22C12 22 4 16 4 10C4 7.87827 4.84285 5.84344 6.34315 4.34315C7.84344 2.84285 9.87827 2 12 2C14.1217 2 16.1566 2.84285 17.6569 4.34315C19.1571 5.84344 20 7.87827 20 10Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M12 13C13.6569 13 15 11.6569 15 10C15 8.34315 13.6569 7 12 7C10.3431 7 9 8.34315 9 10C9 11.6569 10.3431 13 12 13Z" stroke="CurrentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>

                                <div class="text-left">
                                    <h2 class="text-base font-bold text-gray-800 dark:text-gray-300">Vagas na região de:</h2>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">São Caetano do Sul, SP</p>
                                </div>
                            </div>

                            <hr class="border-1.5 border-gray-300 px-[4rem] my-2 dark:border-gray-700">

                            

                            <div class="flex flex-col gap-2 text-left bg-gray-100 dark:bg-gray-900/85 p-3 border-btn shadow-sm">
                                <div class="flex gap-2 p-2 w-full bg-sky-700/5 border-sky-600/10 rounded-full border-[1.5px] text-center justify-center">
                                    <img src="../img/ia.svg" alt="" width="24" height="24">

                                    <h2 class="font-bold text-gray-900 dark:text-gray-200">Vaga <span class="text-sky-600 dark:text-sky-500">destaque:</span></h2>
                                </div>

                                <div class="flex items-center gap-2 p-2 w-full rounded-full">
                                    <div class="flex h-8 w-8 items-center justify-center bg-gray-300 rounded-full text-sm font-semibold text-black">AD</div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-200">@adventree</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-300">Alameda das cidades, 832 (0.5km)</p>
                                    </div>
                                </div>

                                <div class="px-2 pb-3">
                                <p class="text-sm font-semibold text-gray-900 dark:text-gray-200">Garçom</p>
                                <p class="text-xs font-light text-gray-400 dark:text-gray-300">Abra pra ver descrição</p>
                                </div>

                                <img src="../img/small-example-two.jpg" alt="" class="object-cover border-btn">

                            </div>
                        </div>
                    </a>
                </button>
            </div>
        </div>
    </main>

    @auth('web')
    @if(Auth::guard('web')->user()->status == 1)
        @include('partials.onboarding-modal-worker')
    @endif
@endauth
</body>
</html>