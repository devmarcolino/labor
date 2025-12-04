@php
    use App\Models\CandidatoCurtido;
    $empresa = auth('empresa')->user();
    $candidatosCurtidos = $empresa ? CandidatoCurtido::with('user')->where('empresa_id', $empresa->id)->latest()->get() : collect();
@endphp

<div class="flex flex-col gap-4 mt-1 w-full max-w-2xl mx-auto">
    @forelse($candidatosCurtidos as $curtido)
        @php
            $ultimaMsg = App\Models\Mensagem::where(function($q) use ($empresa, $curtido) {
                $q->where('remetente_id', $empresa->id)
                  ->where('remetente_tipo', 'empresa')
                  ->where('destinatario_id', $curtido->user_id)
                  ->where('destinatario_tipo', 'user');
            })->orWhere(function($q) use ($empresa, $curtido) {
                $q->where('remetente_id', $curtido->user_id)
                  ->where('remetente_tipo', 'user')
                  ->where('destinatario_id', $empresa->id)
                  ->where('destinatario_tipo', 'empresa');
            })
            ->orderByDesc('horario')
            ->first();
            $msgTxt = $ultimaMsg ? $ultimaMsg->mensagem : '';
            $msgHora = $ultimaMsg ? \Carbon\Carbon::parse($ultimaMsg->horario)->format('H:i') : '';
            if(strlen($msgTxt) > 40) $msgTxt = mb_substr($msgTxt, 0, 40) . '...';
        @endphp
        @include('partials.chat-user-card', [
            'user' => $curtido->user,
            'mensagem' => $msgTxt,
            'hora' => $msgHora,
            'vaga' => $curtido->vaga
        ])
    @empty
        <div class="text-center text-gray-400 py-8">Nenhum candidato curtido ainda.</div>
    @endforelse
</div>
