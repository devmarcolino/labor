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
<body class="bg-white dark:bg-gray-900  transition-colors duration-500">

<x-loading/>

<div class="flex flex-col justify-between mx-auto items-center self-center min-h-screen text-center">

    <div class="w-full">
      <div class="flex flex-col my-1 sm:gap-5 sm:pt-5">
              <div class="flex justify-between mx-1 items-center gap-12">
                  <x-btn-back/>
              </div>

              <div class="mt-1 w-full bg-gray-200 h-1 dark:bg-gray-700 transition-all duration-300">
                  <div x-ref="progressBar" class="bg-sky-600 h-1"></div>
              </div>
      </div>
    
      

      <form id="loginForm" class="flex flex-col justify-between mx-auto w-full max-w-2xl px-5 py-5 sm:py-9" action="{{ route('login') }}" method="POST">
      @csrf
        <div class="flex flex-col gap-3 text-left">
          <div class="text-left mb-6">
          <h3 class="text-2xl font-bold text-gray-900">Bem-vindo(a) de volta!</h3>
          <p class="text-sm text-gray-700">Que bom te ver novamente. Faça o login para acessar.</p>
          </div>
        
              
        @error('email')
          <x-warn>{{ $message }}</x-warn> 
        @enderror

        <x-input name="email" type="email" placeholder="seu@email.com" value="{{ old('email') }}">
        E-mail
        </x-input>

        <x-input name="password" type="password" placeholder="*******">
          Senha
        </x-input>

        @error('password')
        <x-warn>{{ $message }}</x-warn> 
        @enderror
        </div>
            <div class="flex items-center my-5">
                <input id="remember" name="remember" type="checkbox" value="" class="w-4 h-4 text-sky-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-sky-500 dark:focus:ring-sky-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                <label for="remember" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300"><p class="text-gray-600">Lembrar de mim</p></label>
            </div>

            <div class="text-sm text-right">
              <a href="#" class="font-semibold text-sky-600 hover:underline hover:text-sky-500 dark:text-sky-500 dark:hover:text-sky-400">Esqueceu sua senha?</a>
            </div>
  
  </div>
  </form>
  <div class="navigation-area mx-auto w-full max-w-2xl px-5 py-5 sm:py-9">
        <x-btn-primary type="submit" form="loginForm">Entrar</x-btn-primary>
  </div>
</div>
</body>
</html>