<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Labor for workers</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>
<body class="bg-gray-100 dark:bg-gray-900 transition-colors duration-500">

    <x-loading/>

    <div class="flex justify-center items-center self-center min-h-screen">
        <div class="flex flex-col gap-8 p-5 text-center w-full max-w-2xl">

            <x-header-guest/>
            
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
            
             <x-btn-primary href="{{ url('/auth') }}">Começar</x-btn-primary>
        </div>
    </div>
</body>
</html>