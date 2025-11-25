<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vagas Curtidas</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-50 dark:bg-gray-900">
    <div class="container mx-auto px-2 py-4 max-w-md">
        <div class="flex items-center gap-2 mb-4">
           <x-btn-back />
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">Vagas Curtidas</h1>
        </div>
        <div class="grid grid-cols-1 gap-6">
            @forelse($vagasCurtidas as $curtida)
                @if($curtida->vaga)
                <div class="bg-white rounded-2xl shadow-md p-4 mb-6 dark:bg-gray-800">
                    <div class="flex items-center gap-2 mb-2">
                        @if(!empty($curtida->vaga->empresa) && !empty($curtida->vaga->empresa->fotoEmpresa))
                            <img src="{{ asset('storage/' . $curtida->vaga->empresa->fotoEmpresa) }}" alt="Logo empresa" class="w-8 h-8 rounded-full object-cover">
                        @else
                            <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center text-gray-500 text-xs dark:bg-gray-200">?</div>
                        @endif
                        <div>
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $curtida->vaga->empresa->nome_empresa ?? '' }}</span>
                            <div class="text-xs text-gray-400 dark:text-gray-300">{{ $curtida->vaga->empresa->cidade ?? '' }}</div>
                        </div>
                    </div>
                    <div class="rounded-xl overflow-hidden mb-3">
                        @if(!empty($curtida->vaga->imgVaga))
                            <div class="w-full h-32 flex items-center justify-center rounded-xl overflow-hidden vaga-img-bg" style="background: #fff;">
                                <img src="{{ asset('storage/' . $curtida->vaga->imgVaga) }}" alt="Foto da vaga" class="h-full object-contain vaga-img" style="max-width:100%; max-height:100%;">
                            </div>
                        @else
                            <div class="w-full h-32 bg-gray-50 flex items-center justify-center text-gray-400 text-sm dark:bg-gray-100">Sem imagem da vaga</div>
                        @endif
                    </div>
                    <div class="font-bold text-md text-gray-900 mb-1 dark:text-white">
                        <span class="font-bold text-md text-gray-900 dark:text-white mb-1">{{ $curtida->vaga->tipoVaga ?? '' }} ({{ $curtida->vaga->horario ?? 'horario da vaga' }})</span>
                    </div>
                    <ul class="text-sm text-gray-500 mb-2 list-disc pl-5">
                        <li class="text-gray-500 dark:text-gray-300">{{ $curtida->vaga->descVaga ?? '' }}</li>
                    </ul>
                    <div class="flex gap-6 mb-2">
                        <div class="flex items-center gap-1 text-gray-400 dark:text-gray-200">
                            <img src="/img/heart-handshake.svg" alt="Gostaram" class="w-4 h-4">
                            <span class="text-xs">Curtida em: {{ $curtida->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                    <a href="/vagas/{{ $curtida->vaga->id }}" class="block mt-2 bg-sky-600 text-white font-semibold py-2 rounded-full text-center shadow">Ver detalhes</a>
                </div>
                @endif
            @empty
                <p class="col-span-1 text-center text-gray-500">Nenhuma vaga curtida ainda.</p>
            @endforelse
        </div>
    </div>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.vaga-img').forEach(function(img) {
        img.addEventListener('load', function() {
            try {
                const canvas = document.createElement('canvas');
                canvas.width = img.naturalWidth;
                canvas.height = img.naturalHeight;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0);
                const data = ctx.getImageData(0, 0, canvas.width, canvas.height).data;
                let r = 0, g = 0, b = 0, count = 0;
                for(let i = 0; i < data.length; i += 4*100) { // Amostra pixels para performance
                    r += data[i];
                    g += data[i+1];
                    b += data[i+2];
                    count++;
                }
                r = Math.round(r/count);
                g = Math.round(g/count);
                b = Math.round(b/count);
                const bgColor = `rgb(${r},${g},${b})`;
                img.parentElement.style.background = bgColor;
            } catch(e) {}
        });
    });
});
</script>
</body>
</html>
