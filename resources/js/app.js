// =================================================================
// ARQUIVO: resources/js/app.js (VERSÃO FINAL COMPLETA E CORRIGIDA)
// =================================================================

// --- 1. IMPORTS ---
import "./bootstrap";
import Alpine from "alpinejs";
import interact from "interactjs";
import IMask from "imask";
import "flowbite";
import { Datepicker } from "flowbite-datepicker";
import ptBR from "./flowbite-locale-pt.js";

function cardStack() {
    return {
        cards: [],
        isLoading: true,
        error: null,

        async loadCards() {
            this.isLoading = true;
            this.error = null;

            try {
                const response = await fetch("/api/vagas");
                if (!response.ok) {
                    throw new Error("Erro ao buscar vagas");
                }

                const data = await response.json();
                this.cards = Array.isArray(data) ? data : [];
            } catch (err) {
                console.error("Falha ao carregar vagas", err);
                this.error =
                    "Não foi possível carregar vagas agora. Tente novamente mais tarde.";
                this.cards = [];
            } finally {
                this.isLoading = false;
            }
        },

        // Ativa o card do topo
        activateTopCard() {
            this.$nextTick(() => {
                const cardElements = this.$el.querySelectorAll(".card-item");
                if (cardElements.length > 0) {
                    const topCard = cardElements[0];
                    this.initInteract(topCard);
                }
            });
        },

        // Observa mudanças nos cards
        initWatcher() {
            this.loadCards();
            this.$watch("cards", () => {
                this.activateTopCard();
            });
        },

        // Remove o card do topo
        removeTopCard() {
            this.cards = this.cards.slice(1);
        },

        // Inicializa o Interact.js e duplo clique
        initInteract(element) {
            if (!element || element.classList.contains("interact-enabled"))
                return;
            element.classList.add("interact-enabled");

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
                    { transform: "scale(1)" },
                ];
                const pulseAnimation = element.animate(pulse, {
                    duration: 400,
                    iterations: 1,
                });

                // ❤️ Corações
                for (let i = 0; i < 15; i++) {
                    const heart = document.createElement("div");
                    heart.innerHTML = "❤️";
                    heart.classList.add("floating-heart");
                    heart.style.left = `${Math.random() * 100}vw`;
                    heart.style.fontSize = `${Math.random() * 30 + 40}px`;
                    heart.style.animationDuration = `${
                        Math.random() * 1 + 1.5
                    }s`;
                    document.body.appendChild(heart);
                    heart.addEventListener("animationend", () =>
                        heart.remove()
                    );
                }

                // Quando pulsada terminar, anima subida reta e fluida
                pulseAnimation.onfinish = () => {
                    const rise = element.animate(
                        [
                            { transform: "translate(0px, 0px)" },
                            { transform: "translate(0px, -500px)" },
                        ],
                        {
                            duration: 500,
                            easing: "ease-in-out",
                            fill: "forwards",
                        }
                    );

                    // Remove card ao final
                    rise.onfinish = () => component.removeTopCard();
                };
            });

            // 🧲 Interact.js — arrastar = recusar
            interact(element).draggable({
                onstart: () => {
                    element.style.transition = "none";
                },
                onmove: (event) => {
                    const x =
                        (parseFloat(element.getAttribute("data-x")) || 0) +
                        event.dx;
                    const y =
                        (parseFloat(element.getAttribute("data-y")) || 0) +
                        event.dy;
                    const rotation = x * 0.1;
                    element.style.transform = `translate(${x}px, ${y}px) rotate(${rotation}deg)`;
                    element.setAttribute("data-x", x);
                    element.setAttribute("data-y", y);
                },
                onend: () => {
                    element.style.transition = "transform 0.4s ease-in-out";
                    const totalX =
                        parseFloat(element.getAttribute("data-x")) || 0;
                    const totalY =
                        parseFloat(element.getAttribute("data-y")) || 0;
                    const distance = Math.sqrt(totalX ** 2 + totalY ** 2);

                    if (distance > 10) {
                        // Sai para baixo reto
                        element.animate(
                            [
                                { transform: element.style.transform },
                                { transform: "translate(0px, 500px)" },
                            ],
                            {
                                duration: 400,
                                easing: "ease-in-out",
                                fill: "forwards",
                            }
                        );
                        setTimeout(() => component.removeTopCard(), 400);
                    } else {
                        element.style.transform =
                            "translate(0px, 0px) rotate(0deg)";
                        element.setAttribute("data-x", 0);
                        element.setAttribute("data-y", 0);
                    }
                },
            });
        },
    };
}

/**
 * Componente Alpine para o formulário de registro multi-etapas.
 */
function registrationForm() {
    return {
        step: 1,
        totalSteps: 5, // Ajuste conforme seus steps reais (agora são 2 na modal?)
        errors: {},
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

        // Lógica de Bloqueio do Botão
        get isStepInvalid() {
            // Se estiver verificando no servidor ou tiver erro -> Bloqueia
            if (Object.values(this.isChecking).some(Boolean)) return true;
            if (Object.values(this.errors).some(Boolean)) return true;

            // Validação de campos vazios (Adapte conforme seus steps reais na View)
            switch (this.step) {
                case 1: return !this.fields.nome_real || !this.fields.username;
                case 2: return !this.fields.email;
                case 3: return !this.fields.telefone;
                case 4: return !this.fields.datanasc || !this.fields.cpf;
                case 5: return !this.fields.password || !this.fields.password_confirmation;
                default: return false;
            }
        },

        init() {
            this.$watch('step', () => this.updateProgressBar());
        },

        updateProgressBar() {
            if (this.$refs.progressBar) {
                const percentage = (this.step / this.totalSteps) * 100;
                this.$refs.progressBar.style.width = `${percentage}%`;
            }
        },

        // VALIDAÇÃO COM TOAST
        async validateField(field, type = 'user') {
            const value = this.fields[field];
            
            // 1. Limpa erros anteriores para não ficar vermelho à toa
            // (Exceto se for senha, pois validamos abaixo)
            if (field !== 'password' && field !== 'password_confirmation') {
                this.errors[field] = '';
            }
            
            // === NOVA LÓGICA DE SENHA (LOCAL) ===
            if (field === 'password' || field === 'password_confirmation') {
                
                // A) Valida tamanho da senha (mínimo 8)
                if (this.fields.password && this.fields.password.length < 8) {
                    this.errors.password = 'Mínimo de 8 caracteres.';
                } else {
                    this.errors.password = ''; // Limpa se estiver OK
                }

                // B) Valida se coincidem (só se já começou a digitar a confirmação)
                if (this.fields.password_confirmation) {
                    if (this.fields.password !== this.fields.password_confirmation) {
                        this.errors.password_confirmation = 'As senhas não conferem.';
                    } else {
                        this.errors.password_confirmation = ''; // Limpa se estiver OK
                    }
                }
                
                // PARE AQUI! Não chame a API/Spinner para senha
                return; 
            }
            // ===========================================

            // Para outros campos (email, cpf), se estiver vazio, para aqui.
            if (!value) return;

            this.isChecking[field] = true; // Ativa Spinner

            try {
                const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
                const response = await fetch('/api/validate-field', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf
                    },
                    body: JSON.stringify({ field, value, type })
                });

                const data = await response.json();

                if (!response.ok) {
                    let msg = data.message || 'Dado inválido.';
                    this.errors[field] = msg;
                    
                    // Dispara o Toast Vermelho
                    window.dispatchEvent(new CustomEvent('notify', {
                        detail: { type: 'danger', title: 'Atenção', msg: msg }
                    }));
                } else {
                    // Se validou com sucesso na API, garante que limpa o erro
                    this.errors[field] = '';
                }

            } catch (e) {
                console.error(e);
            } finally {
                this.isChecking[field] = false;
            }
        }
    };
}

function enterpriseForm() {
    return {
        step: 1,
        totalSteps: 5,
        errors: {},
        isChecking: {},
        
        fields: {
            nome_empresa: '',
            ramo: '',
            email: '',
            telefone: '',
            cnpj: '',
            password: '',
            password_confirmation: ''
        },

        get isStepInvalid() {
            if (Object.values(this.isChecking).some(Boolean)) return true;
            if (Object.values(this.errors).some(Boolean)) return true;

            switch (this.step) {
                case 1: return !this.fields.nome_empresa || !this.fields.ramo;
                case 2: return !this.fields.email;
                case 3: return !this.fields.telefone;
                case 4: return !this.fields.cnpj;
                case 5: return !this.fields.password || !this.fields.password_confirmation;
                default: return false;
            }
        },

        init() {
            this.$watch('step', () => this.updateProgressBar());
        },

        updateProgressBar() {
            if (this.$refs.progressBar) {
                const percentage = (this.step / this.totalSteps) * 100;
                this.$refs.progressBar.style.width = `${percentage}%`;
            }
        },

        // VALIDAÇÃO COM TOAST (EMPRESA)
        async validateField(field, type = 'enterprise') {
            const value = this.fields[field];
            
            // 1. Limpa erros anteriores para não ficar vermelho à toa
            // (Exceto se for senha, pois validamos abaixo)
            if (field !== 'password' && field !== 'password_confirmation') {
                this.errors[field] = '';
            }
            
            // === NOVA LÓGICA DE SENHA (LOCAL) ===
            if (field === 'password' || field === 'password_confirmation') {
                
                // A) Valida tamanho da senha (mínimo 8)
                if (this.fields.password && this.fields.password.length < 8) {
                    this.errors.password = 'Mínimo de 8 caracteres.';
                } else {
                    this.errors.password = ''; // Limpa se estiver OK
                }

                // B) Valida se coincidem (só se já começou a digitar a confirmação)
                if (this.fields.password_confirmation) {
                    if (this.fields.password !== this.fields.password_confirmation) {
                        this.errors.password_confirmation = 'As senhas não conferem.';
                    } else {
                        this.errors.password_confirmation = ''; // Limpa se estiver OK
                    }
                }
                
                // PARE AQUI! Não chame a API/Spinner para senha
                return; 
            }
            // ===========================================

            // Para outros campos (email, cpf), se estiver vazio, para aqui.
            if (!value) return;

            this.isChecking[field] = true; // Ativa Spinner

            try {
                const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
                const response = await fetch('/api/validate-field', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf
                    },
                    body: JSON.stringify({ field, value, type })
                });

                const data = await response.json();

                if (!response.ok) {
                    let msg = data.message || 'Dado inválido.';
                    this.errors[field] = msg;
                    
                    // Dispara o Toast Vermelho
                    window.dispatchEvent(new CustomEvent('notify', {
                        detail: { type: 'danger', title: 'Atenção', msg: msg }
                    }));
                } else {
                    // Se validou com sucesso na API, garante que limpa o erro
                    this.errors[field] = '';
                }

            } catch (e) {
                console.error(e);
            } finally {
                this.isChecking[field] = false;
            }
        }
    };
}

// Registro Global do Alpine
window.Alpine = Alpine;
Alpine.data("cardStack", cardStack);
Alpine.data("registrationForm", registrationForm);
Alpine.data("enterpriseForm", enterpriseForm);
Alpine.start();

// --- 4. LÓGICA EXECUTADA APÓS O DOM CARREGAR ---
// =================================================================
// COLE ESTE BLOCO INTEIRO NO LUGAR DO SEU "DOMContentLoaded" ATUAL
// =================================================================
document.addEventListener("DOMContentLoaded", () => {
    console.log("DOM Carregado. Executando scripts adicionais.");

    function applyThemeFromStorage() {
        const savedTheme = localStorage.getItem("theme") || "light"; // Padrão é 'light'
        const html = document.documentElement;

        if (savedTheme === "dark") {
            html.classList.add("dark");
        } else {
            html.classList.remove("dark");
        }
    }

    applyThemeFromStorage();

    window.addEventListener("pageshow", (event) => {
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
            const isDark = document.documentElement.classList.contains("dark");
            if (iconSun && iconMoon) {
                iconSun.style.display = isDark ? "block" : "none";
                iconMoon.style.display = isDark ? "none" : "block";
            }
        };

        // Listener para o clique
        themeToggleBtn.addEventListener("click", () => {
            const currentTheme = localStorage.getItem("theme") || "light";
            const newTheme = currentTheme === "dark" ? "light" : "dark";
            localStorage.setItem("theme", newTheme);

            // Aplica o tema na página e depois atualiza os ícones
            applyThemeFromStorage();
            updateThemeIcons();
        });

        // Atualiza os ícones no carregamento da página
        updateThemeIcons();
    }

    console.log("Procurando por #page-loader...");
    const pageLoader = document.getElementById("page-loader");
    console.log("Elemento encontrado:", pageLoader); // O que aparece aqui?

    if (pageLoader) {
        pageLoader.style.opacity = "0";
        setTimeout(() => {
            pageLoader.style.display = "none";
        }, 500);
    }

    // MÓDULO: CARROSSEL E TÍTULO SINCRONIZADO (A LÓGICA QUE FALTAVA)
    const carouselWrapper =
        document.getElementById("default-carousel")?.parentElement;
    const titleElement = document.getElementById("carousel-title");
    if (carouselWrapper && titleElement) {
        const carouselTexts = [
            "A oportunidade na sua mão.",
            "Gostou da vaga? O trampo é seu.",
            "As vagas que vem até você.",
        ];
        let lastUpdatedIndex = -1;
        const updateTitle = (index) => {
            const numericIndex = parseInt(index);
            if (
                numericIndex !== lastUpdatedIndex &&
                carouselTexts[numericIndex] !== undefined
            ) {
                lastUpdatedIndex = numericIndex;
                titleElement.classList.add("opacity-0");
                setTimeout(() => {
                    titleElement.textContent = carouselTexts[numericIndex];
                    titleElement.classList.remove("opacity-0");
                }, 300);
            }
        };
        const checkActiveAndSetTitle = () => {
            const activeButton = carouselWrapper.querySelector(
                'button[data-carousel-slide-to][aria-current="true"]'
            );
            if (activeButton) {
                const activeIndex = activeButton.getAttribute(
                    "data-carousel-slide-to"
                );
                updateTitle(activeIndex);
            }
        };
        const observer = new MutationObserver(checkActiveAndSetTitle);
        observer.observe(carouselWrapper, {
            attributes: true,
            subtree: true,
            attributeFilter: ["aria-current"],
        });
        checkActiveAndSetTitle();
    }

    // MÓDULO: MÁSCARAS (IMask)
    const fieldsToMask = {
        "#cpf": "000.000.000-00",
        "#datanasc": "00/00/0000",
        "#telefone": "(00) 00000-0000",
        "#cnpj": "00.000.0000/0000-00",
        "#username": "@aaaaaaaaaaaaaaaaaa",
    };
    for (const selector in fieldsToMask) {
        const element = document.querySelector(selector);
        if (element) {
            IMask(element, fieldsToMask[selector]);
        }
    }

    // MÓDULO: DATEPICKER (Flowbite)
    if (typeof Datepicker !== "undefined") {
        if (!Datepicker.locales) Datepicker.locales = {};
        Object.assign(Datepicker.locales, ptBR);
        document.querySelectorAll("[datepicker]").forEach((datepickerEl) => {
            new Datepicker(datepickerEl, {
                language: "pt-BR",
                autohide: true,
                format: "dd/mm/yyyy",
                maxDate: new Date(),
                clearBtn: true,
                todayBtn: true,
                todayBtnMode: 1,
            });
        });
    }
});
