<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Labor</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>
<body class="bg-gray-100 dark:bg-gray-900 transition-colors duration-500">

    <div id="page-loader" class="fixed top-0 left-0 w-full h-full bg-gray-100 dark:bg-gray-900 flex justify-center items-center z-[9999]">
      <div class="animate-spin inline-block size-6 border-[3px] border-current border-t-transparent text-sky-600 rounded-full" role="status" aria-label="loading">
        <span class="sr-only">Carregando...</span>
      </div>
    </div>

    <div class="flex justify-center items-center self-center min-h-screen">
        <div class="flex flex-col gap-8 p-5 text-center w-full max-w-2xl">

            <div class="flex justify-center gap-10">
                <img src="img/logo-h.svg" class="h-100 w-30" alt="Telemóvel">
                
                <button id="theme-toggle" type="button" aria-label="Alternar tema claro/escuro" class="px-3.5 py-1 rounded-full text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                    <svg id="icon-sun" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m8-9h1M3 12H2m15.364-6.364l.707.707M6.343 17.657l-.707.707m12.728 0l-.707.707M6.343 6.343l-.707-.707M12 7a5 5 0 100 10 5 5 0 000-10z" />
                    </svg>
                    <svg id="icon-moon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 hidden" fill="currentColor" viewBox="0 0 20 20" >
                        <path d="M17.293 13.293A8 8 0 116.707 2.707a6 6 0 0010.586 10.586z" />
                    </svg>
                </button>
            </div>
            
            <div class="w-full flex flex-col items-center gap-2">
            <div id="default-carousel" class="relative w-full" data-carousel="slide">
                <div class=" relative overflow-hidden w-full min-h-96 rounded-lg">
                     <div class="hidden duration-700 ease-in-out" data-carousel-item>
                        <img src="img/cellphone.svg" class="absolute block max-h-full max-w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="...">
                    </div>
                    <div class="hidden duration-700 ease-in-out" data-carousel-item>
                        <img src="img/like.svg" class="absolute block max-h-full max-w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="...">
                    </div>
                    <div class="hidden duration-700 ease-in-out" data-carousel-item>
                        <img src="img/message.svg" class="absolute block max-h-full max-w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="...">
                    </div>
                </div>
                
                 <div class="flex flex-col items-center justify-center">
                <div class="flex space-x-3">
                    <button type="button" class="size-3 border bg-transparent border-gray-400 rounded-full transition-all duration-200" aria-current="true" aria-label="Slide 1" data-carousel-slide-to="0"></button>
                    <button type="button" class="size-3 border bg-transparent border-gray-400 rounded-full transition-all duration-200" aria-current="false" aria-label="Slide 2" data-carousel-slide-to="1"></button>
                    <button type="button" class="size-3 border bg-transparent border-gray-400 rounded-full transition-all duration-200" aria-current="false" aria-label="Slide 3" data-carousel-slide-to="2"></button>
                </div>
                </div>
            </div>
            </div>

            <h1 id="carousel-title" class="text-3xl font-black text-gray-900 dark:text-white h-24 flex items-center justify-center transition-opacity duration-300"></h1>
            
            <a class="text-white bg-sky-700 hover:bg-sky-800 focus:ring-2 focus:ring-sky-300/55 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-sky-600 dark:hover:bg-sky-700 focus:outline-none dark:focus:ring-sky-800/55" href="{{ url('/login') }}">Entre ou se cadastre</a>
        </div>
    </div>
</body>
</html>