<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Labor for workers</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <link rel="shortcut icon" href="../img/lb-blue.svg" type="image/x-icon">
</head>
<x-flash-manager />
<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-500 h-screen flex flex-col">
    
    <x-loading/>
</body>
</html>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurações | Labor</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>
<body class="bg-gray-50 dark:bg-gray-900 min-h-screen flex flex-col items-center justify-center">
    <div class="bg-white dark:bg-gray-800 rounded-[40px] shadow-md w-full max-w-md py-8 px-8 flex flex-col items-center">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Configurações</h1>
        <p class="text-gray-700 dark:text-gray-200 text-center">Ajuste suas preferências do sistema.</p>
    </div>
</body>
</html>