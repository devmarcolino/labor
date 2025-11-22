<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar vaga</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>
<body class="bg-gray-50 dark:bg-gray-900 flex flex-col min-h-screen items-center justify-center">
        <x-btn-back />
    <div class="flex flex-col items-center justify-center min-h-screen bg-gray-50 dark:bg-gray-900">
        <div class="bg-white dark:bg-gray-800 shadow-labor border-match w-full max-w-xl p-8">
            <h2 class="text-3xl font-bold mb-8 text-sky-600 dark:text-sky-400 text-center">Criar nova vaga</h2>
            <form method="POST" action="{{ route('enterprises.vagas.store') }}" enctype="multipart/form-data" class="flex flex-col gap-6">
                @csrf
                <div>
                    <label class="block text-gray-700 dark:text-gray-200 font-semibold mb-2">Tipo da vaga</label>
                    <input type="text" name="tipoVaga" class="input-labor border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-sky-500" required>
                </div>
                <div>
                    <label class="block text-gray-700 dark:text-gray-200 font-semibold mb-2">Função</label>
                    <select name="funcVaga" class="input-labor border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-sky-500" required>
                        <option value="">Selecione uma habilidade</option>
                        @foreach($skills as $skill)
                            <option value="{{ $skill->nomeHabilidade }}">{{ $skill->nomeHabilidade }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 dark:text-gray-200 font-semibold mb-2">Descrição</label>
                    <textarea name="descVaga" class="input-labor border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-sky-500" required></textarea>
                </div>
                <div>
                    <label class="block text-gray-700 dark:text-gray-200 font-semibold mb-2">Valor</label>
                    <input type="number" step="0.01" name="valor_vaga" class="input-labor border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-sky-500" required>
                </div>
                <div>
                    <label class="block text-gray-700 dark:text-gray-200 font-semibold mb-2">Data</label>
                    <input type="date" name="dataVaga" class="input-labor border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-sky-500" required>
                </div>
                <div>
                    <label class="block text-gray-700 dark:text-gray-200 font-semibold mb-2">Imagem (opcional)</label>
                    <input type="file" name="imgVaga" accept="image/*" class="input-labor border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>
                <button type="submit" class="btn-labor bg-sky-600 hover:bg-sky-700 text-white font-bold py-3 px-6 rounded-full mt-6 shadow-labor transition-all duration-200">Criar vaga</button>
            </form>
        </div>
    </div>
</body>
</html>
