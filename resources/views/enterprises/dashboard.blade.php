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
</head>
<body class="bg-white dark:bg-gray-900 transition-colors duration-500">
    
<x-loading/>

<header class="flex items-center justify-between py-4 px-5">
    <a href="{{ url('chat') }}"><button><img src="../img/chat-fill.svg" alt="" width="24px" height="24px"></button></a>

    <ul class="flex items-center p-1.5 bg-gray-100 rounded-lg" id="icon-tabs" data-tabs-toggle="#icon-tabs-content" role="tablist">
        <li role="presentation">
            <a href="#" 
            class="flex px-3.5 py-2 rounded-lg text-gray-500 aria-selected:bg-white aria-selected:text-gray-900 aria-selected:shadow-sm" 
            id="flame-tab" data-tabs-target="#flame" type="button" role="tab" aria-controls="flame" aria-selected="true">
                <img src="{{ asset('img/flame.svg') }}" alt="Destaques" width="24" height="24">
            </a>
        </li>
        <li role="presentation">
            <a href="#" 
            class="flex px-3.5 py-2 rounded-lg text-gray-500 aria-selected:bg-white aria-selected:text-gray-900 aria-selected:shadow-sm" 
            id="home-tab" data-tabs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="false">
                <img src="{{ asset('img/home.svg') }}" class="text-gray-700" alt="Início" width="24" height="24">
            </a>
        </li>
    </ul>

    <button><img src="../img/test-avatar.svg" alt=""></button>
</header>

    <div id="icon-tabs-content" class="mt-4 px-5">
    <div class="p-4 rounded-lg bg-gray-100 dark:bg-gray-800" id="flame" role="tabpanel" aria-labelledby="flame-tab">
        <p class="text-sm text-gray-500 dark:text-gray-400">
            Este é o conteúdo da aba <strong class="font-medium text-gray-800 dark:text-white">Destaques (flame)</strong>.
        </p>
    </div>
    <div class="hidden p-4 rounded-lg bg-gray-100 dark:bg-gray-800" id="home" role="tabpanel" aria-labelledby="home-tab">
        <p class="text-sm text-gray-500 dark:text-gray-400">
            Aqui vai o conteúdo da aba <strong class="font-medium text-gray-800 dark:text-white">Início (home)</strong>.
        </p>
    </div>
    </div>

</body>
</html>

<div class="none">Logado com sucesso {{ Auth::user()->nome }}
<form method="POST" action="{{ route('logout') }}">
    @csrf

    <button type="submit">
        Sair
    </button>
</form>
</div>