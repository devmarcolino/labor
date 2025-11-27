<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Labor</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <link rel="shortcut icon" href="img/lb-blue.svg" type="image/x-icon">
</head>
<body class="bg-white dark:bg-gray-900 transition-colors duration-500">

    <x-loading/>

    <div class="flex flex-col justify-between items-center self-center min-h-screen text-center gap-8 px-5 py-5 sm:py-9">

        <div class="w-full">
            <x-header-guest/>
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
            <h1 id="carousel-title" class="text-3xl font-black text-gray-900 dark:text-white h-24 flex items-center justify-center transition-opacity duration-300"></h1>
            </div>

            

            <div class="w-full max-w-2xl">
            <x-btn-primary href="{{ url('/choose') }}">Começar</x-btn-primary>
            </div>
    </div>

</body>
</html>