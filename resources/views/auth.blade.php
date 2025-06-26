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

<div class="flex min-h-screen flex-col p-5 justify-between items-center">

  <x-header-guest/>

  <img src="img/auth.svg" class="w-[17rem] h-auto" alt="">

  <div class="min-w-full">

  

  <div class="text-center mb-8">
    <h2 class="text-3xl font-extrabold">Um clique, <br>uma oportunidade.</h2>
  </div>

   <div class="text-center">
      <x-btn-primary href="{{ url('/login') }}">Entrar</x-btn-primary>
      <x-btn-outline href="{{ url('/register') }}">Criar conta</x-btn-outline>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/preline@3.1.0/dist/preline.min.js"></script>
</body>
</html>