<div class="fixed top-20 right-6 z-50 w-96 bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-4">
    <h3 class="text-lg font-bold text-sky-600 mb-3">Notificações</h3>
    <ul class="space-y-2">
        @forelse($notifications as $notification)
            <li class="bg-gray-100 dark:bg-gray-700 rounded-lg p-3 flex flex-col">
                <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $notification->mensagem }}</span>
                <span class="text-xs text-gray-400">{{ $notification->created_at->diffForHumans() }}</span>
            </li>
        @empty
            <li class="text-gray-400">Nenhuma notificação por enquanto.</li>
        @endforelse
    </ul>
</div>
