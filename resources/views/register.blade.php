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

    <div class="flex flex-col mt-5 sm:gap-3 sm:pt-5">
     <div class="flex justify-center items-center gap-12">
            <x-btn-back/>
            <img src="img/logo-h.svg" class="h-auto w-30" alt="Logo Labor">
            <div class="w-12"></div>
      </div>

      <div class="my-5 w-full bg-gray-200 h-1 dark:bg-gray-700">
          <div class="bg-sky-600 h-1" style="width: 10%"></div>
      </div>
    </div>

    <div class="flex flex-col justify-center">
     
      <div class="mt-5 px-5 sm:mx-auto sm:w-full sm:max-w-sm">
        <form class="flex flex-col gap-3" action="{{ route('register') }}" method="POST">
            @csrf
            
            <x-input name="nome" type="text" placeholder="Neymar Jr" value="{{ old('nome') }}">
              Nome
            </x-input>

            <x-input name="telefone" type="tel" placeholder="(00)00000-0000" value="{{ old('telefone') }}">
              Telefone
            </x-input>

            @error('telefone')
                <x-warn>{{ $message }}</x-warn>
            @enderror

            <x-input name="email" type="email" placeholder="seu@email.com" value="{{ old('email') }}">
              E-mail
            </x-input>

            @error('email')
              <x-warn>{{ $message }}</x-warn>
            @enderror

            <x-input type="text" name="datanasc" datepicker datepicker-format="dd/mm/yyyy" placeholder="00/00/0000" value="{{ old('datanasc') }}">
                
              Data de Nascimento

              <x-slot:icon>
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4Z"/><path d="M0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                    </svg>
                </x-slot:icon>
            </x-input>

            @error('datanasc')
                <x-warn>{{ $message }}</x-warn>
            @enderror

            <x-input name="cpf" type="text" placeholder="000.000.000-00" value="{{ old('cpf') }}">
              CPF
            </x-input>   

            @error('cpf')
                <x-warn>{{ $message }}</x-warn>
            @enderror

            <x-input name="password" type="password" placeholder="*******">
              Senha
            </x-input>

            @error('password')
                <x-warn>{{ $message }}</x-warn>
            @enderror

           <x-input name="password_confirmation" type="password" placeholder="*******">
              Confirme sua senha
            </x-input>

            <div class="mt-5">
              <x-btn-primary type="submit">Criar conta</x-btn-primary>
            </div>
        </form>
      </div>
    </div>
</body>
</html>