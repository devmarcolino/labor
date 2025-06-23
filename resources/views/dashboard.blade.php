Logado com sucesso {{ Auth::user()->nome }}
<form method="POST" action="{{ route('logout') }}">
    @csrf

    <button type="submit">
        Sair
    </button>
</form>