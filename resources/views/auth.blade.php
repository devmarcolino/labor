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
                <div class="flex items-center mt-2 text-sm text-red-600 dark:text-red-500">
                    <svg class="flex-shrink-0 inline w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                    </svg>
                    <span class="font-medium">{{ $message }}</span>
                </div>
      @enderror
    <div class="relative">
      <input type="email" name="email" id="email" class="peer p-4 block w-full bg-gray-50 border-gray-200 rounded-lg sm:text-sm placeholder:text-transparent focus:border-sky-500 focus:ring-sky-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-gray-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600
        focus:pt-6
        focus:pb-2
        [&:not(:placeholder-shown)]:pt-6
        [&:not(:placeholder-shown)]:pb-2
        autofill:pt-6
        autofill:pb-2" placeholder="you@email.com">
      <label for="email" class="absolute top-0 start-0 p-4 h-full sm:text-sm truncate pointer-events-none transition ease-in-out duration-100 border border-transparent origin-[0_0] text-gray-600  dark:text-gray-100 peer-disabled:opacity-50 peer-disabled:pointer-events-none
          peer-focus:scale-90
          peer-focus:translate-x-0.5
          peer-focus:-translate-y-1.5
          peer-focus:text-gray-500 dark:peer-focus:text-gray-300
          peer-[:not(:placeholder-shown)]:scale-90
          peer-[:not(:placeholder-shown)]:translate-x-0.5
          peer-[:not(:placeholder-shown)]:-translate-y-1.5
          peer-[:not(:placeholder-shown)]:text-gray-500 dark:peer-[:not(:placeholder-shown)]:text-gray-300 dark:text-gray-300">E-mail</label>
    </div>

    <div class="relative">
      <input type="password" name="password" id="password" class="peer p-4 block w-full bg-gray-50 border-gray-200 rounded-lg sm:text-sm placeholder:text-transparent focus:border-sky-500 focus:ring-sky-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-gray-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600
        focus:pt-6
        focus:pb-2
        [&:not(:placeholder-shown)]:pt-6
        [&:not(:placeholder-shown)]:pb-2
        autofill:pt-6
        autofill:pb-2" placeholder="********">
      <label for="password" class="absolute top-0 start-0 p-4 h-full sm:text-sm truncate pointer-events-none transition ease-in-out duration-100 border border-transparent origin-[0_0] text-gray-600  dark:text-gray-100 peer-disabled:opacity-50 peer-disabled:pointer-events-none
          peer-focus:scale-90
          peer-focus:translate-x-0.5
          peer-focus:-translate-y-1.5
          peer-focus:text-gray-500 dark:peer-focus:text-gray-300
          peer-[:not(:placeholder-shown)]:scale-90
          peer-[:not(:placeholder-shown)]:translate-x-0.5
          peer-[:not(:placeholder-shown)]:-translate-y-1.5
          peer-[:not(:placeholder-shown)]:text-gray-500 dark:peer-[:not(:placeholder-shown)]:text-gray-300 dark:text-gray-300">Senha</label>
    </div>

     @error('password')
                <div class="flex items-center mt-2 text-sm text-red-600 dark:text-red-500">
                    <svg class="flex-shrink-0 inline w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                    </svg>
                    <span class="font-medium">{{ $message }}</span>
                </div>
    @enderror

    <div class="flex items-center my-5">
        <input id="remember" type="checkbox" value="" class="w-4 h-4 text-sky-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-sky-500 dark:focus:ring-sky-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
        <label for="remember" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Lembrar de mim</label>
    </div>

    <div class="text-sm text-right">
      <a href="#" class="font-semibold text-sky-600 hover:underline hover:text-sky-500 dark:text-sky-500 dark:hover:text-sky-400">Esqueceu sua senha?</a>
    </div>
  </div>

  <div>
    <x-btn-primary type="submit">Entrar</x-btn-primary>
  </div>
</form>

    <p class="mt-10 text-center text-sm/6 text-gray-500">
      Não tem uma conta ainda?
      <a href="{{ url('/register') }}" class="font-semibold hover:underline text-sky-600 hover:text-sky-500">Crie uma agora.</a>
    </p>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/preline@3.1.0/dist/preline.min.js"></script>
</body>
</html>