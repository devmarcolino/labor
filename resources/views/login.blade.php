<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Labor Login</title>
   @vite('resources/css/app.css')
   @vite('resources/js/app.js')
</head>
<body class="bg-gray-100 dark:bg-neutral-900 transition-colors duration-500">

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
    <form class="space-y-6" action="#" method="POST">
      <div class="max-w-sm space-y-3">
        <div class="relative">
          <input type="email" class="peer py-2.5 sm:py-3 px-4 ps-11 block w-full bg-gray-50 border-transparent focus:outline-gray-300 rounded-lg sm:text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-700 dark:border-transparent dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-gray-700 dark:focus:outline-gray-800" placeholder="Insira seu e-mail">
          <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4 peer-disabled:opacity-50 peer-disabled:pointer-events-none">
            <svg class="shrink-0 size-4 text-gray-500 dark:text-neutral-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
              <circle cx="12" cy="7" r="4"></circle>
            </svg>
          </div>
        </div>

        <div class="relative">
          <input type="password" class="peer py-2.5 sm:py-3 px-4 ps-11 block w-full bg-gray-50 border-transparent focus:outline-gray-300 rounded-lg sm:text-sm focus:border-blue-500 focus:ring-gray-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-700 dark:border-transparent dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-gray-700 dark:focus:outline-gray-800" placeholder="Insira sua senha">
          <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4 peer-disabled:opacity-50 peer-disabled:pointer-events-none">
            <svg class="shrink-0 size-4 text-gray-500 dark:text-neutral-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M2 18v3c0 .6.4 1 1 1h4v-3h3v-3h2l1.4-1.4a6.5 6.5 0 1 0-4-4Z"></path>
              <circle cx="16.5" cy="7.5" r=".5"></circle>
            </svg>
          </div>
        </div>

        <div class="text-sm text-right">
          <a href="#" class="font-semibold text-sky-600 hover:text-sky-500 dark:text-sky-500 dark:hover:text-sky-400">Esqueceu sua senha?</a>
        </div>
      </div>

      <div>
        <button type="submit" class="text-white bg-sky-700 hover:bg-sky-800 focus:ring-2 focus:ring-sky-300/55 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-sky-600 dark:hover:bg-sky-700 focus:outline-none dark:focus:ring-sky-800/55 w-full">Entrar</button>
      </div>
    </form>

    <p class="mt-10 text-center text-sm/6 text-gray-500">
      Não tem uma conta ainda?
      <a href="{{ url('/cadastro') }}" class="font-semibold text-sky-600 hover:text-sky-500">Crie uma agora.</a>
    </p>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/preline@2.3.0/dist/preline.min.js"></script>
</body>
</html>