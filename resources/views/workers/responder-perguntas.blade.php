
@php
    $user_id = Auth::id();
    $idHabilidade = request('idHabilidade'); // ou defina conforme sua lógica
    $perguntas = \App\Models\Pergunta::where('idHabilidade', $idHabilidade)->get();
@endphp

<form method="POST" action="{{ route('workers.salvarRespostas') }}">
    @csrf
    <input type="hidden" name="idHabilidade" value="{{ $idHabilidade }}">
    @foreach($perguntas as $pergunta)
        @php
            $opcoes = json_decode($pergunta->opcoes, true) ?? ["Ruim","Regular","Bom","Ótimo","Excelente"];
        @endphp
        <div class="mb-4">
            <label class="block font-semibold mb-2">{{ $pergunta->texto }}</label>
            <select name="respostas[{{ $pergunta->id }}]" class="w-full p-2 border rounded">
                <option value="">Selecione</option>
                @foreach($opcoes as $opcao)
                    <option value="{{ $opcao }}">{{ $opcao }}</option>
                @endforeach
            </select>
        </div>
    @endforeach
    <button type="submit" class="bg-sky-600 text-white px-4 py-2 rounded">Salvar respostas</button>
</form>
