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

<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-500 h-screen flex flex-col">
    
    <x-loading/>

    <header class="flex items-center justify-between py-3 px-5">

        <button class="icon-btn">
            <a href="{{ url(path: 'enterprises/chat') }}" class="text-gray-500 dark:text-gray-400">
                <svg width="24" height="24" fill="currentColor" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d='M11.953 2.25c-2.317 0-4.118 0-5.52.15-1.418.153-2.541.47-3.437 1.186-.92.736-1.35 1.693-1.553 2.9-.193 1.152-.193 2.618-.193 4.446v.183c0 1.782 0 3.015.2 3.934.108.495.278.925.545 1.323.264.392.6.722 1.001 1.042.631.505 1.375.81 2.254 1V21a.75.75 0 0 0 1.123.65c.586-.335 1.105-.7 1.58-1.044l.304-.221a22 22 0 0 1 1.036-.73c.844-.548 1.65-.905 2.707-.905h.047c2.317 0 4.118 0 5.52-.15 1.418-.153 2.541-.47 3.437-1.186.4-.32.737-.65 1-1.042.268-.398.438-.828.546-1.323.2-.919.2-2.152.2-3.934v-.183c0-1.828 0-3.294-.193-4.445-.203-1.208-.633-2.165-1.553-2.901-.896-.717-2.019-1.033-3.437-1.185-1.402-.151-3.203-.151-5.52-.151z'/></svg>
            </a>
        </button>

        <ul class="flex items-center py-1.5 px-2.5 bg-gray-100 dark:bg-gray-800 rounded-full shadow-sm" id="icon-tabs" data-tabs-toggle="#icon-tabs-content" role="tablist">
            <li role="presentation">
                <a href="{{ url('enterprises/dashboard') }}" 
                class="group flex px-5 py-2 rounded-full text-gray-500 dark:text-gray-300 aria-selected:bg-white aria-selected:dark:bg-gray-950/85 aria-selected:text-sky-600 aria-selected:shadow-sm transition-all ease-linear duration-200"
                id="flame-tab" data-tabs-target="#flame" type="button" role="tab" aria-controls="flame" aria-selected="false">
                    
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
                <a href="{{ url('enterprises/dashboard') }}" 
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

            <a href="{{ url('enterprises/account') }}" class="text-gray-600 hover:text-gray-900 shadow-labor">
                <div class="bg-gray-200 bg-center bg-cover bg-no-repeat w-[40px] h-[40px] rounded-full shadow-md" style="background-image: url('{{ asset('img/Ferreirinha.jpg') }}');">
                </div>
            </a>
    </header>

    <div class="flex flex-col w-full max-w-2xl mx-auto justify-center py-3 px-5 items-center">
      <div class="flex gap-3 items-center py-3 px-5 w-full max-w-2xl border border-gray-200 bg-white shadow-md rounded-full">
        <img src="../img/zoom.svg" alt="">
        <input type="text" id="search" name="search" class="w-full border-transparent focus:border-none">
      </div>
    </div>
    </body>
</html>
