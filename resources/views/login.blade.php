<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Labor Login</title>
   @vite('resources/css/app.css')
   @vite('resources/js/app.js')
</head>
<body class="bg-gray-100 dark:bg-gray-900 transition-colors duration-500">

<div id="page-loader" class="fixed top-0 left-0 w-full h-full bg-gray-100 dark:bg-gray-900 flex justify-center items-center z-[9999]">
  <div class="animate-spin inline-block size-6 border-[3px] border-current border-t-transparent text-sky-600 rounded-full" role="status" aria-label="loading">
    <span class="sr-only">Carregando...</span>
  </div>
</div>

<div class="flex min-h-screen flex-col px-5 justify-center">
  <div class="flex justify-center gap-10">
            <img src="img/logo-h.svg" class="h-100 w-30" alt="Telemóvel">
            
                <button id="theme-toggle" type="button" aria-label="Alternar tema claro/escuro" class="px-3.5 py-1 rounded-full text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                    <svg id="icon-sun" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m8-9h1M3 12H2m15.364-6.364l.707.707M6.343 17.657l-.707.707m12.728 0l-.707.707M6.343 6.343l-.707-.707M12 7a5 5 0 100 10 5 5 0 000-10z" />
                    </svg>
                    <svg id="icon-moon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 hidden" fill="currentColor" viewBox="0 0 20 20" >
                        <path d="M17.293 13.293A8 8 0 116.707 2.707a6 6 0 0010.586 10.586z" />
                    </svg>
                </button>
        </div>

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
    <button type="submit" class="text-white bg-sky-700 hover:bg-sky-800 focus:ring-2 focus:ring-sky-300/55 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-sky-600 dark:hover:bg-sky-700 focus:outline-none dark:focus:ring-sky-800/55 w-full">Entrar</button>
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