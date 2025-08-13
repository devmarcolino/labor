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

    <ul class="flex items-center p-1.5 bg-gray-100 text-sm font-medium text-center text-gray-500 rounded-lg dark:text-gray-400">
        <li><a class="flex px-3.5 py-2 text-white bg-white rounded-lg active" href=""><img src="../img/flame.svg" alt="" width="24px" height="24px"></a></li>
        <li><a class="flex px-3.5 py-2 text-white rounded-lg" href=""><img src="../img/home.svg" alt="" width="24px" height="24px" ></a></li>
    </ul>

    <button><img src="../img/test-avatar.svg" alt=""></button>
</header>

</body>
</html>

<div class="hidden">Logado com sucesso {{ Auth::user()->nome }}
<form method="POST" action="{{ route('logout') }}">
    @csrf

    <button type="submit">
        Sair
    </button>
</form>
</div>