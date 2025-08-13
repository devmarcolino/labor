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
</head>
<body class="bg-white dark:bg-gray-900 transition-colors duration-500">

<x-loading/>

<div class="flex min-h-screen flex-col p-5 justify-between items-center">

  <x-header-guest/>

  <img src="img/auth.svg" class="w-[17rem] h-auto" alt="">

  <div class="min-w-full">

  

  <div class="text-center mb-8">
    <h2 class="text-3xl font-extrabold">Seja bem-vindo a Labor!</h2>
  </div>

   <div class="text-center">
      <x-btn-primary href="{{ url('/workers/auth') }}">Trabalhador</x-btn-primary>
      <x-btn-outline href="{{ url('') }}">Empresa</x-btn-outline>
  </div>
</div>
</body>
</html>