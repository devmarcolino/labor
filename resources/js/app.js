// =================================================================
// ARQUIVO: resources/js/app.js (VERSÃO FINAL COMPLETA E CORRIGIDA)
// =================================================================

// --- 1. IMPORTS ---
import './bootstrap';
import Alpine from 'alpinejs';
import interact from 'interactjs';
import IMask from 'imask';
import 'flowbite';
import { Datepicker } from 'flowbite-datepicker';
import ptBR from './flowbite-locale-pt.js';

// Em: resources/js/app.js

function cardStack() {
    return {
        cards: [
            { id: 1, title: 'Garçom', company: 'Adventree Buffet e Eventos',  ramo: 'Buffet', logo: 'AD',desc: 'Trabalhe como garçom em...', image: '/img/match-example.png' },
            { id: 2, title: 'Barista', company: 'Café do Bairro', ramo: 'Cafeteria', logo: 'CB', desc: 'Venha trabalhar conosco...', image: '/img/match-example-1.png' },
            { id: 3, title: 'Recepcionista', company: 'Hotel Central',  ramo: 'Festas e Eventos', logo: 'HC', desc: 'Vaga de recepcionista em...', image: '/img/match-example-2.png' },
            { id: 4, title: 'Monitor', company: 'Bete Bolinhas Coloridas', ramo: 'Festas e Eventos', logo: 'BC',desc: 'Vaga de monitoria...', image: '/img/match-example-3.jpg' }
        ],

        // Ativa o card do topo
        activateTopCard() {
            this.$nextTick(() => {
                const cardElements = this.$el.querySelectorAll('.card-item');
                if (cardElements.length > 0) {
                    const topCard = cardElements[0];
                    this.initInteract(topCard);
                }
            });
        },

        // Observa mudanças nos cards
        initWatcher() {
            this.activateTopCard();
            this.$watch('cards', () => {
                this.activateTopCard();
            });
        },

        // Remove o card do topo
        removeTopCard() {
            this.cards = this.cards.slice(1);
        },

        // Inicializa o Interact.js e duplo clique
        initInteract(element) {
            if (!element || element.classList.contains('interact-enabled')) return;
            element.classList.add('interact-enabled');

            const component = this;

            // 🎯 Duplo clique = curtir
            element.addEventListener("dblclick", () => {
                // Borda vermelha
                element.style.border = "4px solid #e63946";

                // Pulsada
                const pulse = [
                    { transform: "scale(1)" },
                    { transform: "scale(1.1)" },
                    { transform: "scale(0.98)" },
                    { transform: "scale(1)" }
                ];
                const pulseAnimation = element.animate(pulse, { duration: 400, iterations: 1 });

                // ❤️ Corações
                for (let i = 0; i < 15; i++) {
                    const heart = document.createElement("div");
                    heart.innerHTML = "❤️";
                    heart.classList.add("floating-heart");
                    heart.style.left = `${Math.random() * 100}vw`;
                    heart.style.fontSize = `${Math.random() * 30 + 40}px`;
                    heart.style.animationDuration = `${Math.random() * 1 + 1.5}s`;
                    document.body.appendChild(heart);
                    heart.addEventListener("animationend", () => heart.remove());
                }

                // Quando pulsada terminar, anima subida reta e fluida
                pulseAnimation.onfinish = () => {
                    const rise = element.animate(
                        [
                            { transform: "translate(0px, 0px)" },
                            { transform: "translate(0px, -500px)" }
                        ],
                        { duration: 500, easing: "ease-in-out", fill: "forwards" }
                    );

                    // Remove card ao final
                    rise.onfinish = () => component.removeTopCard();
                };
            });

            // 🧲 Interact.js — arrastar = recusar
            interact(element).draggable({
                onstart: () => { element.style.transition = 'none'; },
                onmove: (event) => {
                    const x = (parseFloat(element.getAttribute('data-x')) || 0) + event.dx;
                    const y = (parseFloat(element.getAttribute('data-y')) || 0) + event.dy;
                    const rotation = x * 0.1;
                    element.style.transform = `translate(${x}px, ${y}px) rotate(${rotation}deg)`;
                    element.setAttribute('data-x', x);
                    element.setAttribute('data-y', y);
                },
                onend: () => {
                    element.style.transition = 'transform 0.4s ease-in-out';
                    const totalX = parseFloat(element.getAttribute('data-x')) || 0;
                    const totalY = parseFloat(element.getAttribute('data-y')) || 0;
                    const distance = Math.sqrt(totalX ** 2 + totalY ** 2);

                    if (distance > 10) {
                        // Sai para baixo reto
                        element.animate(
                            [
                                { transform: element.style.transform },
                                { transform: "translate(0px, 500px)" }
                            ],
                            { duration: 400, easing: "ease-in-out", fill: "forwards" }
                        );
                        setTimeout(() => component.removeTopCard(), 400);
                    } else {
                        element.style.transform = 'translate(0px, 0px) rotate(0deg)';
                        element.setAttribute('data-x', 0);
                        element.setAttribute('data-y', 0);
                    }
                }
            });
        }
    }
}

/**
 * Componente Alpine para o formulário de registro multi-etapas.
 */
function registrationForm() {
    return {
        step: 1,
        totalSteps: 5,
        states: [],
        cities: [],
        cep: '',
        selectedState: { sigla: '', nome: 'Selecione um Estado' },
        selectedCity: '',
        stateDropdownOpen: false,
        cityDropdownOpen: false,
        stateSearch: '',
        citySearch: '',
        isLoadingStates: true,
        isLoadingCities: false,
        isLoadingCep: false,
        cepError: '',
        errors: {},       // Para guardar erros (ex: { email: 'Email já em uso' })
        isChecking: {},

        fields: {
            nome_real: '',
            username: '',
            email: '',
            telefone: '',
            datanasc: '',
            cpf: '',
            password: '',
            password_confirmation: ''
        },

        // --- 3. PROPRIEDADE COMPUTADA (A MÁGICA) ---
        // Isso aqui é uma função que "calcula" se o botão deve estar desabilitado
        get isStepInvalid() {
            // Checa se algum campo da etapa atual está sendo verificado
            const checking = Object.keys(this.isChecking).some(key => this.isChecking[key]);
            if (checking) return true; // Bloqueia se estiver verificando

            // Checa se algum campo da etapa atual tem erro
            const hasError = Object.keys(this.errors).some(key => this.errors[key]);
            if (hasError) return true; // Bloqueia se tiver erro

            // Checa se os campos da etapa atual estão vazios
            switch (this.step) {
                case 1:
                    return !this.fields.nome_real || !this.fields.username;
                case 2:
                    return !this.fields.email;
                case 3:
                    return !this.fields.telefone;
                case 4:
                    return !this.fields.datanasc || !this.fields.cpf;
                case 5: // Última etapa (senha)
                    return !this.fields.password || !this.fields.password_confirmation;
                default:
                    return false;
            }
        },
        
        init() {
            this.fetchStates();
            this.$watch('step', () => this.updateProgressBar());
            this.$nextTick(() => this.updateProgressBar());
        },
        updateProgressBar() {
            if (this.$refs.progressBar) {
                const percentage = (this.step / this.totalSteps) * 100;
                this.$refs.progressBar.style.width = `${percentage}%`;
            }
        },
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

        validateField(event, field, type = 'user') {
            const value = event.target.value;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            this.errors[field] = '';
            if (!value) {
                return;
            }

            this.isChecking[field] = true;

            fetch('/api/validate-field', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    field: field,
                    value: value,
                    type: type 
                })
            })
            .then(response => {
                if (!response.ok) {
                    // Se a resposta for 422, pega o JSON do erro e rejeita
                    return response.json().then(err => Promise.reject(err));
                }
                return response.json();
            })
            .then(data => {
                // Deu certo (200 OK), campo é válido
                this.errors[field] = '';
            })
            .catch(errorData => {
                
                // === AQUI ESTÁ A CORREÇÃO ===
                // O 'errorData' é o JSON que o Laravel mandou, ex: { email: ["O email já existe"] }
                // A chave do erro (ex: 'email') é a mesma variável 'field'
                
                if (errorData[field] && errorData[field].length > 0) {
                    // Se achou o erro, coloca na variável 'errors'
                    this.errors[field] = errorData[field][0];
                } else {
                    // Erro genérico se o JSON vier quebrado
                    console.error("Resposta de erro 422 inesperada:", errorData);
                    this.errors[field] = "Erro inesperado ao validar.";
                }
                // ==============================
                
            })
            .finally(() => {
                this.isChecking[field] = false;
            });
        }
    };
}


// --- 3. INICIALIZAÇÃO DO ALPINE ---
window.Alpine = Alpine;
Alpine.data('cardStack', cardStack);
Alpine.data('registrationForm', registrationForm);
Alpine.start();

// --- 4. LÓGICA EXECUTADA APÓS O DOM CARREGAR ---
// =================================================================
// COLE ESTE BLOCO INTEIRO NO LUGAR DO SEU "DOMContentLoaded" ATUAL
// =================================================================
document.addEventListener("DOMContentLoaded", () => {
    console.log("DOM Carregado. Executando scripts adicionais.");

function applyThemeFromStorage() {
    const savedTheme = localStorage.getItem('theme') || 'light'; // Padrão é 'light'
    const html = document.documentElement;

    if (savedTheme === "dark") {
        html.classList.add("dark");
    } else {
        html.classList.remove("dark");
    }
}

applyThemeFromStorage();

window.addEventListener('pageshow', (event) => {
    if (event.persisted) {
        console.log("Página restaurada do cache. Re-aplicando tema...");
        applyThemeFromStorage();
    }
});

const themeToggleBtn = document.getElementById("theme-toggle");
if (themeToggleBtn) {
    const iconSun = document.getElementById("icon-sun");
    const iconMoon = document.getElementById("icon-moon");

    // Função para atualizar apenas os ícones sol/lua
    const updateThemeIcons = () => {
        const isDark = document.documentElement.classList.contains('dark');
        if (iconSun && iconMoon) {
            iconSun.style.display = isDark ? 'block' : 'none';
            iconMoon.style.display = isDark ? 'none' : 'block';
        }
    };
    
    // Listener para o clique
    themeToggleBtn.addEventListener("click", () => {
        const currentTheme = localStorage.getItem('theme') || 'light';
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        localStorage.setItem("theme", newTheme);
        
        // Aplica o tema na página e depois atualiza os ícones
        applyThemeFromStorage();
        updateThemeIcons();
    });

    // Atualiza os ícones no carregamento da página
    updateThemeIcons();
}

    console.log("Procurando por #page-loader...");
const pageLoader = document.getElementById('page-loader');
console.log("Elemento encontrado:", pageLoader); // O que aparece aqui?

if (pageLoader) {
    pageLoader.style.opacity = '0';
    setTimeout(() => {
        pageLoader.style.display = 'none';
    }, 500);
}

    // MÓDULO: CARROSSEL E TÍTULO SINCRONIZADO (A LÓGICA QUE FALTAVA)
    const carouselWrapper = document.getElementById('default-carousel')?.parentElement;
    const titleElement = document.getElementById('carousel-title');
    if (carouselWrapper && titleElement) {
        const carouselTexts = [
            "A oportunidade na sua mão.",
            "Gostou da vaga? O trampo é seu.",
            "As vagas que vem até você."
        ];
        let lastUpdatedIndex = -1;
        const updateTitle = (index) => {
            const numericIndex = parseInt(index);
            if (numericIndex !== lastUpdatedIndex && carouselTexts[numericIndex] !== undefined) {
                lastUpdatedIndex = numericIndex;
                titleElement.classList.add('opacity-0');
                setTimeout(() => {
                    titleElement.textContent = carouselTexts[numericIndex];
                    titleElement.classList.remove('opacity-0');
                }, 300);
            }
        };
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
        checkActiveAndSetTitle();
    }

    // MÓDULO: MÁSCARAS (IMask)
    const fieldsToMask = {
        '#cpf': '000.000.000-00',
        '#datanasc': '00/00/0000',
        '#telefone': '(00) 00000-0000',
        '#cnpj':'00.000.0000/0000-00',
        '#username': '@aaaaaaaaaaaaaaaaaa'
    };
    for (const selector in fieldsToMask) {
        const element = document.querySelector(selector);
        if (element) {
            IMask(element, fieldsToMask[selector]);
        }
    }

    // MÓDULO: DATEPICKER (Flowbite)
    if (typeof Datepicker !== 'undefined') {
        if (!Datepicker.locales) Datepicker.locales = {};
        Object.assign(Datepicker.locales, ptBR);
        document.querySelectorAll('[datepicker]').forEach((datepickerEl) => {
            new Datepicker(datepickerEl, {
                language: 'pt-BR',
                autohide: true,
                format: 'dd/mm/yyyy',
                maxDate: new Date(),
                clearBtn: true,
                todayBtn: true,
                todayBtnMode: 1,
            });
        });
    }
});