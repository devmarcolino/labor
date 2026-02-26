<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
      <link rel="manifest" href="/manifest.webmanifest">
      <meta name="theme-color" content="#0ea5e9">
      <meta name="mobile-web-app-capable" content="yes">
      <link rel="apple-touch-icon" href="/img/auth-worker.png">
    <title>Labor for workers</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <link rel="shortcut icon" href="../img/lb-blue.svg" type="image/x-icon">
</head>
<body class="bg-white dark:bg-gray-900 transition-colors duration-500">

<x-loading/>

<div class="flex flex-col justify-between items-center self-center min-h-screen text-center gap-8 px-5 py-5 sm:py-9">

  <div class="w-full">
    <x-header-labor/>
  </div>

  <div class="flex flex-col items-center justify-center w-full max-w-2xl gap-8">
    <img src="../img/auth-enterprise.png" class="w-[17rem] h-auto" alt="">

    <h2 class="text-3xl font-black text-gray-900 dark:text-white">Labor para<br> empresas</h2>
  </div>

   <div class="navigation-area mx-auto w-full max-w-2xl">
      <x-btn-primary href="{{ url('/enterprises/login') }}">Entrar</x-btn-primary>
      <x-btn-outline href="{{ url('/enterprises/register') }}">Criar conta</x-btn-outline>
  </div>
</div>
</body>
</html>