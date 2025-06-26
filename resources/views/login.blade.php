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

<div class="flex min-h-screen flex-col px-5 justify-center">

  <x-header-guest/>

  <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
<form class="space-y-6" action="{{ route('login') }}" method="POST">
  @csrf

  <div class="max-w-sm space-y-3">
      
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

    <div class="flex items-center my-5">
        <input id="remember" type="checkbox" value="" class="w-4 h-4 text-sky-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-sky-500 dark:focus:ring-sky-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
        <label for="remember" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300"><p class="text-gray-600">Lembrar de mim</p></label>
    </div>

    <div class="text-sm text-right">
      <a href="#" class="font-semibold text-sky-600 hover:underline hover:text-sky-500 dark:text-sky-500 dark:hover:text-sky-400">Esqueceu sua senha?</a>
    </div>
  </div>

  <div>
    <x-btn-primary type="submit">Entrar</x-btn-primary>
  </div>
</form>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/preline@3.1.0/dist/preline.min.js"></script>
</body>
</html>