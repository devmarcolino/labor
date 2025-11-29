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
    <link rel="shortcut icon" href="../img/lb-blue.svg" type="image/x-icon">
</head>

<x-flash-manager />

<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-500 min-h-screen">

  <x-loading/>

  <header class="p-2 flex justify-between w-full">
    <x-btn-account/>
    <x-btn-theme/>
  </header>

    <div class="flex flex-col self-center items-center w-full gap-5 text-center px-5 py-5 sm:py-9">

      <div class="bg-white dark:bg-gray-800 flex flex-col items-center justify-center text-center rounded-[40px] shadow-md w-full max-w-2xl py-5 px-5 mt-[5rem]">

        <div class="bg-gray-200 bg-center bg-cover bg-no-repeat w-[120px] h-[120px] rounded-full shadow-md relative mt-[-6rem]" 
             style="background-image: url('{{ Auth::user()->fotoUser ? asset("storage/" . Auth::user()->fotoUser) : "" }}');">

          @if(Auth::user()->fotoUser)
            <img src="{{ asset('storage/' . Auth::user()->fotoUser) }}" alt="Foto de perfil" class="w-full h-full object-cover rounded-full" />
          @else
            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
               <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
          @endif

        </div>

        <div class="my-2">
          <p class="font-[300] text-gray-900 dark:text-white">{{ $user->username }}</p>
          <h3 class="font-bold  text-gray-900 dark:text-white">{{ $user->nome_real }}</h3>
        </div>

        <hr class="border-1.5 border-gray-300 px-[4rem] my-2 dark:border-gray-700">

        <div class="shadow-sm bg-gray-50/15 dark:bg-gray-800 dark:border-gray-600 flex items-center justify-between gap-3 w-full border border-gray-300 rounded-full px-5 py-5 mt-2">
          <p class="text-gray-800 dark:text-gray-200">(0)</p>
          <div class="flex">
            <img src="../img/star-stroke.svg" alt="">
            <img src="../img/star-stroke.svg" alt="">
            <img src="../img/star-stroke.svg" alt="">
            <img src="../img/star-stroke.svg" alt="">
            <img src="../img/star-stroke.svg" alt="">
          </div>

          <svg class="text-gray-900 dark:text-gray-200" width="24" height="24" viewBox="0 0 24 24" fill="none">
            <path d="M12 5L19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </div>
      </div>

      <div class="bg-white dark:bg-gray-800 items-center justify-center text-center rounded-[40px] shadow-md w-full max-w-2xl py-3 px-5">

        <!-- Botão MINHAS HABILIDADES -->
        <x-btn-outline-account>
          <div onclick="document.getElementById('modal-habilidades').classList.remove('hidden')" class="w-full flex items-center gap-2 justify-center cursor-pointer">
            <svg class="text-gray-800 dark:text-gray-100" width="24" height="24" viewBox="0 0 24 24" fill="none">
              <path d="M4 22H20C20.5304 22 21.0391 21.7893 21.4142 21.4142C21.7893 21.0391 22 20.5304 22 20V4C22 3.46957 21.7893 2.96086 21.4142 2.58579C21.0391 2.21071 20.5304 2 20 2H8C7.46957 2 6.96086 2.21071 6.58579 2.58579C6.21071 2.96086 6 3.46957 6 4V20C6 20.5304 5.78929 21.0391 5.41421 21.4142C5.03914 21.7893 4.53043 22 4 22Z" stroke="CurrentColor" stroke-width="2"/>
              <path d="M18 14H10" stroke="CurrentColor" stroke-width="2"/>
              <path d="M15 18H10" stroke="CurrentColor" stroke-width="2"/>
              <path d="M10 6H18V10H10V6Z" stroke="CurrentColor" stroke-width="2"/>
            </svg>
            Minhas Habilidades
          </div>
        </x-btn-outline-account>

        <!-- MODAL DE HABILIDADES -->
        <div id="modal-habilidades"
     class="fixed inset-0 z-50 flex items-center justify-center 
            bg-gray-900 bg-opacity-25 backdrop-blur-sm p-4 hidden">

  <div class="relative w-full max-w-md rounded-[50px] bg-white dark:bg-gray-800 
              pt-10 pb-6 px-6 shadow-2xl flex flex-col max-h-[90vh] overflow-x-hidden">

    <button onclick="document.getElementById('modal-habilidades').classList.add('hidden')" 
            class="absolute top-4 right-5 text-2xl text-gray-500 hover:text-gray-800 dark:hover:text-gray-300">
      &times;
    </button>

    <h2 class="text-xl font-bold mb-4 text-center text-gray-900 dark:text-white">
      Minhas Habilidades
    </h2>

    <form method="POST" action="{{ route('workers.update.skills') }}">
      @csrf

      <div class="grid grid-cols-2 gap-2 max-h-60 overflow-y-auto mb-4 px-1">
        @foreach($habilidades as $skill)
          <label class="relative flex items-center p-3 rounded-lg border border-gray-200 cursor-pointer hover:bg-sky-50 transition-colors dark:border-gray-700 dark:hover:bg-gray-700">
            <input type="checkbox" 
                   name="habilidades[]" 
                   value="{{ $skill->id }}" 
                   @if(in_array($skill->id, $userSkills)) checked @endif
                   onchange="abrirPerguntas({{ $skill->id }})">
            <span class="text-gray-800 dark:text-gray-200">{{ $skill->nomeHabilidade }}</span>
          </label>
        @endforeach
      </div>

      <div class="flex justify-end gap-2 mt-3">
        <button type="button"
                onclick="document.getElementById('modal-habilidades').classList.add('hidden')" 
                class="px-4 py-2 rounded bg-gray-300 dark:bg-gray-700 text-gray-900 dark:text-white">
          Cancelar
        </button>

        <button type="submit" 
                class="px-4 py-2 rounded bg-sky-600 text-white font-bold hover:bg-sky-700">
          Salvar
        </button>
      </div>
    </form>

  </div>
</div>

        <!-- Botão MEU ENDEREÇO -->
        <x-btn-outline-account>
          <div class="w-full flex items-center gap-2 justify-center cursor-pointer">
            <svg class="text-gray-800 dark:text-gray-100" width="24" height="24" fill="none">
              <path d="M20 10C20 16 12 22 12 22C12 22 4 16 4 10C4 7.878 4.843 5.843 6.343 4.343C7.843 2.843 9.878 2 12 2C14.122 2 16.157 2.843 17.657 4.343C19.157 5.843 20 7.878 20 10Z" stroke="currentColor" stroke-width="2"/>
              <path d="M12 13C13.657 13 15 11.657 15 10C15 8.343 13.657 7 12 7C10.343 7 9 8.343 9 10C9 11.657 10.343 13 12 13Z" stroke="CurrentColor" stroke-width="2"/>
            </svg>
            Meu endereço
          </div>
        </x-btn-outline-account>

        <!-- Botão CONFIGURAÇÕES -->
        <x-btn-outline-account>
          <a href="{{ route('workers.settings') }}" class="w-full flex items-center gap-2 justify-center cursor-pointer">
            <svg class="text-gray-800 dark:text-gray-100" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path d="M12.22 2H11.78C11.2496 2 10.7409 2.21071 10.3658 2.58579C9.99072 2.96086 9.78 3.46957 9.78 4V4.18C9.77964 4.53073 9.68706 4.87519 9.51154 5.17884C9.33602 5.48248 9.08374 5.73464 8.78 5.91L8.35 6.16C8.04596 6.33554 7.70108 6.42795 7.35 6.42795C6.99893 6.42795 6.65404 6.33554 6.35 6.16L6.2 6.08C5.74107 5.81526 5.19584 5.74344 4.684 5.88031C4.17217 6.01717 3.73555 6.35154 3.47 6.81L3.25 7.19C2.98526 7.64893 2.91345 8.19416 3.05031 8.706C3.18717 9.21783 3.52154 9.65445 3.98 9.92L4.13 10.02C4.43228 10.1945 4.68362 10.4451 4.85905 10.7468C5.03448 11.0486 5.1279 11.391 5.13 11.74V12.25C5.1314 12.6024 5.03965 12.949 4.86405 13.2545C4.68844 13.5601 4.43521 13.8138 4.13 13.99L3.98 14.08C3.52154 14.3456 3.18717 14.7822 3.05031 15.294C2.91345 15.8058 2.98526 16.3511 3.25 16.81L3.47 17.19C3.73555 17.6485 4.17217 17.9828 4.684 18.1197C5.19584 18.2566 5.74107 18.1847 6.2 17.92L6.35 17.84C6.65404 17.6645 6.99893 17.5721 7.35 17.5721C7.70108 17.5721 8.04596 17.6645 8.35 17.84L8.78 18.09C9.08374 18.2654 9.33602 18.5175 9.51154 18.8212C9.68706 19.1248 9.77964 19.4693 9.78 19.82V20C9.78 20.5304 9.99072 21.0391 10.3658 21.4142C10.7409 21.7893 11.2496 22 11.78 22H12.22C12.7504 22 13.2591 21.7893 13.6342 21.4142C14.0093 21.0391 14.22 20.5304 14.22 20V19.82C14.2204 19.4693 14.3129 19.1248 14.4885 18.8212C14.664 18.5175 14.9163 18.2654 15.22 18.09L15.65 17.84C15.954 17.6645 16.2989 17.5721 16.65 17.5721C17.0011 17.5721 17.346 17.6645 17.65 17.84L17.8 17.92C18.2589 18.1847 18.8042 18.2566 19.316 18.1197C19.8278 17.9828 20.2645 17.6485 20.53 17.19L20.75 16.8C21.0147 16.3411 21.0866 15.7958 20.9497 15.284C20.8128 14.7722 20.4785 14.3356 20.02 14.07L19.87 13.99C19.5648 13.8138 19.3116 13.5601 19.136 13.2545C18.9604 12.949 18.8686 12.6024 18.87 12.25V11.75C18.8686 11.3976 18.9604 11.051 19.136 10.7455C19.3116 10.4399 19.5648 10.1862 19.87 10.01L20.02 9.92C20.4785 9.65445 20.8128 9.21783 20.9497 8.706C21.0866 8.19416 21.0147 7.64893 20.75 7.19L20.53 6.81C20.2645 6.35154 19.8278 6.01717 19.316 5.88031C18.8042 5.74344 18.2589 5.81526 17.8 6.08L17.65 6.16C17.346 6.33554 17.0011 6.42795 16.65 6.42795C16.2989 6.42795 15.954 6.33554 15.65 6.16L15.22 5.91C14.9163 5.73464 14.664 5.48248 14.4885 5.17884C14.3129 4.87519 14.2204 4.53073 14.22 4.18V4C14.22 3.47 14.0093 2.961 13.6342 2.58579C13.2591 2.21071 12.7504 2 12.22 2Z" stroke="CurrentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      <path d="M12 15C13.6569 15 15 13.6569 15 12C15 10.3431 13.6569 9 12 9C10.3431 9 9 10.3431 9 12C9 13.6569 10.3431 15 12 15Z" stroke="CurrentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
    Configurações
  </a>
</x-btn-outline-account>

        <!-- Botão LOGOUT -->
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <x-btn-red type="submit">
            <img src="../img/logout.svg" alt="">
            Sair da conta
          </x-btn-red>
        </form>

      </div>
  </div>

  <!-- Modal de perguntas -->
<div id="modal-perguntas" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
  <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-lg p-6 w-full max-w-md relative">
    <button onclick="document.getElementById('modal-perguntas').classList.add('hidden')" class="absolute top-2 right-2 text-gray-500 hover:text-gray-900">&times;</button>
    <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Responda as perguntas</h2>
    <form id="form-perguntas">
      <div id="perguntas-container"></div>
      <div class="flex justify-end gap-2 mt-4">
        <button type="button" onclick="document.getElementById('modal-perguntas').classList.add('hidden')" class="px-4 py-2 rounded bg-gray-300 dark:bg-gray-700 text-gray-900 dark:text-white">Cancelar</button>
        <button type="submit" class="px-4 py-2 rounded bg-sky-600 text-white font-bold hover:bg-sky-700">Salvar</button>
      </div>
    </form>
  </div>
</div>
<script>
function abrirPerguntas(idHabilidade) {
  fetch('{{ route('workers.perguntas.habilidade') }}', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    body: JSON.stringify({ idHabilidade })
  })
  .then(res => res.json())
  .then(perguntas => {
    let html = '';
    perguntas.forEach(p => {
      html += `<div class=\"mb-3\">
        <label class=\"block text-gray-800 dark:text-gray-200 font-semibold mb-1\">${p.texto}</label>
        <select name=\"respostas[${p.id}]\" class=\"input-labor w-full border rounded px-3 py-2\" required>\n          <option value=\"\">Selecione</option>\n          <option value=\"Ruim\">Ruim</option>\n          <option value=\"Regular\">Regular</option>\n          <option value=\"Bom\">Bom</option>\n          <option value=\"Ótimo\">Ótimo</option>\n          <option value=\"Excelente\">Excelente</option>\n        </select>
      </div>`;
    });
    document.getElementById('perguntas-container').innerHTML = html;
    document.getElementById('modal-perguntas').classList.remove('hidden');
    document.getElementById('form-perguntas').onsubmit = function(e) {
      e.preventDefault();
      const formData = new FormData(e.target);
      formData.append('idHabilidade', idHabilidade);
      fetch('{{ route('workers.salvar.respostas.perguntas') }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: formData
      })
      .then(res => res.json())
      .then(resp => {
        if (resp.success) {
          alert('Respostas salvas!');
          document.getElementById('modal-perguntas').classList.add('hidden');
        }
      });
    };
  });
}
</script>
</body>
</html>
