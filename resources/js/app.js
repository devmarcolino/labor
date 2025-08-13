// =================================================================
// ARQUIVO: resources/js/app.js
// =================================================================

// --- 1. CONFIGURAÇÕES INICIAIS ---
import './bootstrap';
import IMask from 'imask';
import 'flowbite';
import Alpine from 'alpinejs';
import { Carousel } from 'flowbite';

// NOVO CÓDIGO DE VALIDAÇÃO - SUBSTITUA O ANTIGO
// NOVO CÓDIGO DE VALIDAÇÃO (VERSÃO 3 - CORRIGIDA)

function updateButtonState() {
    console.log("--- Validando etapa... ---");

    const form = document.querySelector('form[x-data="registrationForm"]');
    if (!form) return;

    const stepContainers = form.querySelectorAll(':scope > div[x-show]');
    
    const activeStepContainer = Array.from(stepContainers).find(
        (div) => div.offsetParent !== null
    );

    if (!activeStepContainer) {
        console.warn("AVISO: Nenhum container de etapa ativo foi encontrado.");
        return;
    }
    console.log("Container da etapa ativa encontrado:", activeStepContainer);

    const inputs = activeStepContainer.querySelectorAll("[validate-input]");
    const buttons = document.querySelectorAll("[validate-btn]");

    console.log(`Inputs nesta etapa: ${inputs.length}`);

    const allFilled = Array.from(inputs).every(
        (input) => {
            // AQUI ESTÁ A CORREÇÃO!
            // Agora procuramos pelo nosso atributo 'data-dropdown-container', que é um seletor válido.
            if (input.closest('[data-dropdown-container]')) {
                return true; // Se o input está dentro de um dropdown, ignore a validação.
            }
            return input.value.trim() !== "";
        }
    );

    console.log(`Todos os campos preenchidos? ${allFilled}`);

    buttons.forEach((button) => {
        if(button.textContent.toLowerCase().includes('voltar')){
             button.disabled = false;
             return;
        }
        button.disabled = !allFilled;
    });
}

document.addEventListener('alpine:init', () => {
    console.log("✅ Alpine.js inicializado. Configurando validação...");
    setTimeout(updateButtonState, 150); 

    const inputsToValidate = document.querySelectorAll("[validate-input]");
    inputsToValidate.forEach(input => {
        input.addEventListener('input', updateButtonState);
    });

    const navButtons = document.querySelectorAll('button[validate-btn]');
    navButtons.forEach(button => {
        button.addEventListener('click', () => {
            setTimeout(updateButtonState, 150); 
        });
    });
});
// FIM DO NOVO CÓDIGO



function registrationForm() {
    return {
        // --- Estado do formulário ---
        step: 1,
        totalSteps: 5, // O número total de passos do seu formulário

        // --- Variáveis de Dados de Localização ---
        states: [],
        cities: [],
        cep: '',
        selectedState: { sigla: '', nome: '' },
        selectedCity: '',
        
        // --- Variáveis de Controle da UI (Interface) ---
        stateDropdownOpen: false,
        cityDropdownOpen: false,
        stateSearch: '',
        citySearch: '',
        isLoadingStates: true,
        isLoadingCities: false,
        isLoadingCep: false,
        cepError: '',

        // --- Funções (Nossas Ações) ---
        fetchStates() {
            fetch('https://servicodados.ibge.gov.br/api/v1/localidades/estados?orderBy=nome')
                .then(r => r.json())
                .then(data => { this.states = data; this.isLoadingStates = false; });
        },
        fetchCities() {
            if (!this.selectedState.sigla) return;
            this.isLoadingCities = true;
            this.cities = [];
            this.selectedCity = '';
            fetch(`https://servicodados.ibge.gov.br/api/v1/localidades/estados/${this.selectedState.sigla}/municipios`)
                .then(r => r.json())
                .then(data => { this.cities = data; this.isLoadingCities = false; });
        },
        fetchAddressByCep() {
            const cleanCep = this.cep.replace(/\D/g, '');
            if (cleanCep.length !== 8) { this.cepError = ''; return; }
            this.isLoadingCep = true;
            this.cepError = '';
            fetch(`https://viacep.com.br/ws/${cleanCep}/json/`)
                .then(response => response.json())
                .then(data => {
                    if (data.erro) {
                        this.cepError = 'CEP não encontrado.';
                        this.isLoadingCep = false;
                        return;
                    }
                    const foundState = this.states.find(s => s.sigla === data.uf);
                    if (foundState) { this.selectedState = foundState; }
                    this.selectedCity = data.localidade;
                    this.isLoadingCep = false;
                });
        }
    };
}

// PASSO 2: O REGISTRO (O PASSO CRÍTICO)
// Apresentamos nossa 'fábrica' para o Alpine com o nome 'registrationForm'
Alpine.data('registrationForm', registrationForm);

// PASSO 3: A INICIALIZAÇÃO

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
    const dataNascInput = document.getElementById('datanasc');
    const telInput = document.getElementById('telefone');
    const userInput = document.getElementById('user');
    // Aplica a máscara de CPF, se o campo existir na página
    if (telInput) {
        IMask(telInput, {
            mask: '(00) 00000-0000'
        });
    }

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

    if (userInput) {
        IMask(userInput, {
            mask: '@aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa'
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