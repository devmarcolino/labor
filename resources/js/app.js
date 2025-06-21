// =================================================================
// ARQUIVO: resources/js/app.js
// =================================================================

// --- 1. CONFIGURAÇÕES INICIAIS ---
import './bootstrap';
import IMask from 'imask';
import 'flowbite';
import Alpine from 'alpinejs';
import { Carousel } from 'flowbite';

window.Alpine = Alpine;
Alpine.start()

import ptBR from './flowbite-locale-pt.js';

// Garante que o locale seja registrado
if (!Datepicker.locales) Datepicker.locales = {};
Object.assign(Datepicker.locales, ptBR);

// Inicializa


// --- 2. LÓGICA PRINCIPAL (executa quando o DOM está pronto) ---
document.addEventListener("DOMContentLoaded", () => {

    // ========================================================
    // MÓDULO: TEMA CLARO / ESCURO (DARK MODE)
    // ========================================================
    const themeToggleBtn = document.getElementById("theme-toggle");
const iconSun = document.getElementById("icon-sun");
const iconMoon = document.getElementById("icon-moon");
const html = document.documentElement;

if (themeToggleBtn && iconSun && iconMoon) {
    
    // A função que aplica o tema na prática
    const setTheme = (theme) => {
        if (theme === "dark") {
            html.classList.add("dark");
            iconMoon.classList.add("hidden");
            iconSun.classList.remove("hidden");
        } else {
            html.classList.remove("dark");
            iconMoon.classList.remove("hidden");
            iconSun.classList.add("hidden");
        }
        localStorage.setItem("theme", theme);
    };

    // A LÓGICA CORRIGIDA PARA O TEMA PADRÃO
    if (localStorage.getItem('theme') === 'dark') {
        // Se o usuário já navegou e ativou o modo escuro, mantenha.
        setTheme('dark');
    } else {
        // Para novos visitantes ou qualquer outro caso, o padrão é 'light'.
        setTheme('light');
    }

    // A lógica para o clique no botão continua a mesma
    themeToggleBtn.addEventListener("click", () => {
        if (html.classList.contains("dark")) {
            setTheme("light");
        } else {
            setTheme("dark");
        }
    });
}

    // ========================================================
    // MÓDULO: CARREGADOR DE PÁGINA (PAGE LOADER)
    // ========================================================
    const pageLoader = document.getElementById('page-loader');

    if (pageLoader) {
        // Esconde o loader inicial
        pageLoader.style.opacity = '0';
        setTimeout(() => {
            pageLoader.style.display = 'none';
        }, 500); // Garante que a transição de opacidade termine

        // Mostra o loader ao navegar
        document.querySelectorAll('a[href]:not([href^="#"]):not([target="_blank"])').forEach(link => {
            link.addEventListener('click', () => pageLoader.style.display = 'flex');
        });

        // Lida com o botão "voltar" do navegador
        window.addEventListener('pageshow', (event) => {
            if (event.persisted) {
                pageLoader.style.display = 'none';
            }
        });
    }


    // ========================================================
    // MÓDULO: CARROSSEL E TÍTULO SINCRONIZADO (LÓGICA CORRETA)
    // ========================================================
    // ========================================================
const carouselWrapper = document.getElementById('default-carousel')?.parentElement;
const titleElement = document.getElementById('carousel-title');

// A condição agora só verifica se o wrapper e o título existem
if (carouselWrapper && titleElement) {

    // Voltamos ao array simples apenas com os títulos
    const carouselTexts = [
        "A oportunidade na sua mão.",
        "Gostou da vaga? O trampo é seu.",
        "As vagas que vem até você."
    ];

    let lastUpdatedIndex = -1;

    // A função de update foi simplificada para cuidar apenas do título
    const updateTitle = (index) => {
        const numericIndex = parseInt(index);
        
        if (numericIndex !== lastUpdatedIndex && carouselTexts[numericIndex] !== undefined) {
            lastUpdatedIndex = numericIndex;
            
            // Animação de fade-out
            titleElement.classList.add('opacity-0');
            
            setTimeout(() => {
                // Troca o texto e aplica fade-in
                titleElement.textContent = carouselTexts[numericIndex];
                titleElement.classList.remove('opacity-0');
            }, 300); // Mantenha este tempo igual à classe 'duration-300' do HTML
        }
    };
    
    // O resto da lógica (o "espião") continua exatamente igual
    const checkActiveAndSetTitle = () => {
        const activeButton = carouselWrapper.querySelector('button[data-carousel-slide-to][aria-current="true"]');
        if (activeButton) {
            const activeIndex = activeButton.getAttribute('data-carousel-slide-to');
            updateTitle(activeIndex);
        }
    };

    const observer = new MutationObserver(checkActiveAndSetTitle);

    observer.observe(carouselWrapper, {
        attributes: true,
        subtree: true,
        attributeFilter: ['aria-current']
    });

    // Define o conteúdo inicial
    checkActiveAndSetTitle();
}
    const cpfInput = document.getElementById('cpf');
    const dataNascInput = document.getElementById('data-nasc');

    // Aplica a máscara de CPF, se o campo existir na página
    if (cpfInput) {
        IMask(cpfInput, {
            mask: '000.000.000-00'
        });
    }

    // Aplica a máscara de Data, se o campo existir na página
    if (dataNascInput) {
        IMask(dataNascInput, {
            mask: '00/00/0000'
        });
    }

    const datepickerEls = document.querySelectorAll('[datepicker]');

if (datepickerEls.length > 0 && window.Datepicker) {
    datepickerEls.forEach((datepickerEl) => {
        const datepickerInstance = new Datepicker(datepickerEl, {
            language: 'pt-BR',
            autohide: true,
            format: 'dd/mm/yyyy',
            maxDate: new Date(),
            clearBtn: true,
            todayBtn: true,
            todayBtnMode: 1,
        });

        datepickerEl.addEventListener('show', () => {
            if (datepickerEl.value === '') {
                const eighteenYearsAgo = new Date();
                eighteenYearsAgo.setFullYear(new Date().getFullYear() - 18);
                datepickerInstance.setDate(eighteenYearsAgo);
            }
        });
    });
}
});