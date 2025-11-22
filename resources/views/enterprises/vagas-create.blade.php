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
    <div class="flex flex-col items-center justify-center min-h-screen bg-gray-50 dark:bg-gray-900">
        <div class="bg-white dark:bg-gray-800 rounded-[40px] shadow-md w-full max-w-xl p-8">
            <h2 class="text-2xl font-bold mb-6 text-sky-600 dark:text-sky-400">Criar nova vaga</h2>
            <form method="POST" action="{{ route('enterprises.vagas.store') }}" enctype="multipart/form-data" class="flex flex-col gap-4">
                @csrf
                <div>
                    <label class="block text-gray-700 dark:text-gray-200 font-semibold mb-2">Tipo da vaga</label>
                    <input type="text" name="tipoVaga" class="input-labor" required>
                </div>
                <div>
                    <label class="block text-gray-700 dark:text-gray-200 font-semibold mb-2">Função</label>
                    <select name="funcVaga" class="input-labor" required>
                        <option value="">Selecione uma habilidade</option>
                        @foreach($skills as $skill)
                            <option value="{{ $skill->nomeHabilidade }}">{{ $skill->nomeHabilidade }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 dark:text-gray-200 font-semibold mb-2">Descrição</label>
                    <textarea name="descVaga" class="input-labor" required></textarea>
                </div>
                <div>
                    <label class="block text-gray-700 dark:text-gray-200 font-semibold mb-2">Valor</label>
                    <input type="number" step="0.01" name="valor_vaga" class="input-labor" required>
                </div>
                <div>
                    <label class="block text-gray-700 dark:text-gray-200 font-semibold mb-2">Data</label>
                    <input type="date" name="dataVaga" class="input-labor" required>
                </div>
                <div>
                    <label class="block text-gray-700 dark:text-gray-200 font-semibold mb-2">Imagem (opcional)</label>
                    <input type="file" name="imgVaga" accept="image/*" class="input-labor">
                </div>
                <button type="submit" class="btn-labor bg-sky-600 text-white font-bold py-2 px-4 rounded-full mt-4">Criar vaga</button>
            </form>
        </div>
    </div>
</body>
</html>
