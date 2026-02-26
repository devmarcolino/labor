<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="manifest" href="/manifest.webmanifest">
    <meta name="theme-color" content="#0ea5e9">
    <meta name="mobile-web-app-capable" content="yes">
    <link rel="apple-touch-icon" href="/img/auth-worker.png">
    <title>Labor for workers</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <link rel="shortcut icon" href="../img/lb-blue.svg" type="image/x-icon">
</head>
<x-flash-manager />
<body x-data="settingsManager()" class="bg-gray-50 dark:bg-gray-900 transition-colors duration-500 h-screen flex flex-col">
    
    <x-loading/>

    <header class="flex justify-center w-full mx-auto pt-4 mb-2">
        <div class="flex items-center justify-between w-full max-w-2xl px-5 relative">
            <a href="{{ route('workers.account') }}" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-800 transition-colors">
                <svg class="w-6 h-6 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            
            <h1 class="text-xl font-bold text-center dark:text-white">Configurações</h1>
        </div>
    </header>

    <main class="flex-1 w-full max-w-md mx-auto px-5 pt-6">
        
        <div class="bg-white dark:bg-gray-800 rounded-[30px] p-6 shadow-labor border border-gray-100 dark:border-gray-700">
            
            <div class="grid grid-cols-2 gap-6 mb-8">
    
    <div class="flex flex-col items-center gap-3">
        <span class="text-sm text-gray-900 dark:text-white">Modo escuro</span>
        
        <div class="flex items-center border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 rounded-full p-2 gap-2 justify-between cursor-pointer shadow-sm"
             @click="toggleTheme()">
            
            <div class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-300 transform"
                 :class="isDark ? 'bg-sky-600 text-white shadow-md' : 'text-gray-400 dark:text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-700'">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
            </div>

            <div class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-300 transform"
                 :class="!isDark ? 'bg-sky-600 text-white shadow-md' : 'text-gray-400 dark:text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-700'">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m8-9h1M3 12H2m15.364-6.364l.707.707M6.343 17.657l-.707.707m12.728 0l-.707.707M6.343 6.343l-.707-.707M12 7a5 5 0 100 10 5 5 0 000-10z"/></svg>
            </div>
        </div>
    </div>

    <div class="flex flex-col items-center gap-3">
        <span class="text-sm text-gray-900 dark:text-white">Notificações</span>
        
        <div class="flex items-center border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 rounded-full p-2 gap-2 justify-between cursor-pointer shadow-sm"
             @click="toggleNotifications()">
            
            <div class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-300 transform"
                 :class="!notificationsEnabled ? 'bg-sky-600 text-white shadow-md' : 'text-gray-400 dark:text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-700'">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" clip-rule="evenodd"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2"/></svg>
            </div>

            <div class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-300 transform"
                 :class="notificationsEnabled ? 'bg-sky-600 text-white shadow-md' : 'text-gray-400 dark:text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-700'">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            </div>
        </div>
    </div>
</div>

            <div class="w-full h-px bg-gray-100 dark:bg-gray-700 mb-6"></div>

            <div class="flex flex-col gap-1">
                <h3 class="text-sm font-medium text-gray-800 dark:text-gray-200 text-center mb-4">Configurações de acessibilidade</h3>

                <x-btn-primary @click="changeFont(1)">
                        <svg class="w-6 h-6 border-2 border-white/30 rounded-full p-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Aumentar letras</span>
                    
                </x-btn-primary>

                <x-btn-outline @click="changeFont(-1)">
                    <svg class="w-6 h-6 border-2 border-sky-200 rounded-full p-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                    <span>Diminuir letras</span>
                </x-btn-outline>
            </div>
        </div>
    </main>

    <script>
    function settingsManager() {
        return {
            // Inicializa verificando se o tema salvo é 'dark'
            isDark: (localStorage.getItem('theme') || 'light') === 'dark',
            notificationsEnabled: true,
            fontLevel: parseInt(localStorage.getItem('font-level') || '1'),

            toggleTheme() {
                // Inverte o valor booleano
                this.isDark = !this.isDark;
                
                // Salva a string correta pro app.js ler
                const themeString = this.isDark ? 'dark' : 'light';
                localStorage.setItem('theme', themeString);
                
                // Chama a função global para aplicar o CSS na hora
                if (window.applyThemeGlobal) window.applyThemeGlobal();
            },

            toggleNotifications() {
                this.notificationsEnabled = !this.notificationsEnabled;
                // Aqui entraria o fetch para o backend
            },

            changeFont(direction) {
                let newLevel = this.fontLevel + direction;
                if (newLevel >= 0 && newLevel <= 3) {
                    this.fontLevel = newLevel;
                    localStorage.setItem('font-level', this.fontLevel);
                    if (window.applyFontGlobal) window.applyFontGlobal();
                }
            },

            getLevelLabel() {
                const labels = ['Pequeno', 'Normal', 'Grande', 'Extra Grande'];
                return labels[this.fontLevel] || 'Normal';
            }
        }
    }
</script>
</body>
</html>
