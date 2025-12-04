<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Labor for enterprises</title>
    
    <!-- O Vite vai carregar o app.js que contém o Alpine e ApexCharts agora -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link rel="shortcut icon" href="../img/lb-blue.svg" type="image/x-icon">
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>

<x-flash-manager />

<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-500 h-screen flex flex-col">
    
    <x-loading/>

    <header class="flex justify-center w-full mx-auto pt-4 mb-2">
        <div class="flex items-center justify-between w-full max-w-2xl px-5 relative">
            <a href="{{ route('enterprises.dashboard') }}" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-800 transition-colors">
                <svg class="w-6 h-6 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            
            <div class="flex items-center gap-2">
                <img src="../img/ia.svg" alt="" class="w-5 h-5">
                <h1 class="text-lg font-bold text-gray-900 dark:text-white">Relatório de gastos</h1>
            </div>
        </div>
    </header>

    <main class="max-w-2xl w-full mx-auto px-5">
        
        <!-- CARD BRANCO PRINCIPAL -->
        <div class="bg-white dark:bg-gray-800 rounded-[35px] p-6 shadow-sm min-h-[500px]"
             x-data="analyticsChart()">

            <!-- Título do Card -->
            <div class="mb-6">
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-6 h-6 rounded-full border-2 border-gray-900 dark:border-white flex items-center justify-center">
                        <div class="w-2 h-2 bg-gray-900 dark:bg-white rounded-full"></div>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Gastos do mês</h2>
                </div>
                <p class="text-gray-400 text-xs pl-8">Analise por Labor IA ©</p>
            </div>

            <!-- LEGENDA CUSTOMIZADA -->
            <div class="flex flex-wrap justify-center gap-4 mb-6">
                <template x-for="(label, index) in labels" :key="index">
                    <div class="flex items-center gap-2">
                        <!-- Pílula Colorida -->
                        <div class="w-8 h-3 rounded-full" :style="'background-color: ' + colors[index]"></div>
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-200" x-text="label"></span>
                    </div>
                </template>
            </div>

            <!-- ÁREA DO GRÁFICO -->
            <div class="relative flex justify-center items-center">
                <div id="chart" class="w-full flex justify-center"></div>
                
                <!-- Valor Total no Centro -->
                <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                    <div class="text-center mt-2">
                        <p class="text-2xl font-extrabold text-gray-900 dark:text-white tracking-tight" x-text="'R$' + totalFormatted"></p>
                    </div>
                </div>
            </div>

        </div>

    </main>

    <script>
        // Função do AlpineJS
        function analyticsChart() {
            return {
                colors: ['#2563EB', '#FBBF24', '#C026D3', '#E74694', '#16BDCA'], 
                
                // Dados injetados pelo Laravel (Agora com nomes corretos vindos do controller)
                labels: @json($labelsGrafico), 
                series: @json($seriesGrafico), 
                total: {{ $totalGasto }},

                get totalFormatted() {
                    return this.total.toLocaleString('pt-BR', { minimumFractionDigits: 0, maximumFractionDigits: 2 });
                },

                init() {
                    // Pequeno delay para garantir que o DOM e o ApexCharts carregaram via Vite
                    setTimeout(() => {
                        this.renderChart();
                    }, 100);
                },

                renderChart() {
                    // Verifica se o ApexCharts existe (carregado via app.js)
                    if (typeof ApexCharts === 'undefined') {
                        console.error('ApexCharts não carregado. Verifique npm run dev.');
                        return;
                    }

                    const options = {
                        series: this.series,
                        labels: this.labels,
                        chart: {
                            type: 'donut',
                            width: '100%',
                            height: 340,
                            fontFamily: 'Inter, sans-serif',
                            toolbar: { show: false },
                            animations: { enabled: true }
                        },
                        colors: this.colors,
                        stroke: { show: false },
                        plotOptions: {
                            pie: {
                                donut: {
                                    size: '72%',
                                    labels: { show: false }
                                }
                            }
                        },
                        dataLabels: { enabled: false },
                        legend: { show: false },
                        tooltip: {
                            enabled: true,
                            y: {
                                formatter: function(value) {
                                    return 'R$ ' + value.toLocaleString('pt-BR', { minimumFractionDigits: 2 });
                                }
                            }
                        },
                        states: {
                            hover: { filter: { type: 'none' } }, 
                            active: { filter: { type: 'none' } }
                        }
                    };

                    const chart = new ApexCharts(document.querySelector("#chart"), options);
                    chart.render();
                }
            }
        }
    </script>
</body>
</html>