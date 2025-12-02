@php
    use App\Models\CandidatoCurtido;
    $empresa = auth('empresa')->user();
    $candidatosCurtidos = $empresa ? CandidatoCurtido::with('user')->where('empresa_id', $empresa->id)->latest()->get() : collect();
@endphp

<div class="flex flex-col gap-4 mt-8 w-full max-w-xl mx-auto">
    @forelse($candidatosCurtidos as $curtido)
        @include('partials.chat-user-card', [
            'user' => $curtido->user,
            'mensagem' => 'Lorem impsen', // Aqui você pode colocar a última mensagem real se tiver
            'hora' => $curtido->created_at ? $curtido->created_at->format('H:i') : ''
        ])
    @empty
        <div class="text-center text-gray-400 py-8">Nenhum candidato curtido ainda.</div>
    @endforelse
</div>
