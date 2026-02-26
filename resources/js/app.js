import "./bootstrap";
import Alpine from "alpinejs";
import interact from "interactjs";
import IMask from "imask";
import "flowbite";
import { Datepicker } from "flowbite-datepicker";
import ApexCharts from "apexcharts";
import ptBR from "./flowbite-locale-pt.js";

// Service Worker Registration para PWA
if ("serviceWorker" in navigator) {
    window.addEventListener("load", () => {
        navigator.serviceWorker
            .register("/sw.js", { scope: "/" })
            .then((registration) => {
                console.log(
                    "Service Worker registered successfully",
                    registration,
                );

                // Check for updates every hour
                setInterval(
                    () => {
                        registration.update();
                    },
                    60 * 60 * 1000,
                );
            })
            .catch((error) => {
                console.error("Service Worker registration failed", error);
            });
    });

    // Handle updates to service worker
    if (navigator.serviceWorker.controller) {
        navigator.serviceWorker.addEventListener("controllerchange", () => {
            console.log("Service Worker updated");
            // Optionally notify user about app update
        });
    }
}

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
                // Filtra vagas já interagidas
                const interacoes = await fetch(
                    "/workers/vagas-interacoes-json",
                ).then((res) => res.json());
                const interagidasIds = Object.keys(interacoes).map((id) =>
                    parseInt(id),
                );
                this.cards = Array.isArray(data)
                    ? data.filter((v) => !interagidasIds.includes(v.id))
                    : [];
            } catch (err) {
                console.error("Falha ao carregar vagas", err);
                this.error =
                    "Não foi possível carregar vagas agora. Tente novamente mais tarde.";
                this.cards = [];
            } finally {
                this.isLoading = false;
            }
        },

        // Reativa o topo quando muda o array
        initWatcher() {
            this.loadCards();
            this.$watch("cards", () => this.activateTopCard());
        },

        // Remove o topo e ativa o próximo
        removeTopCard() {
            this.cards = this.cards.slice(1);

            this.$nextTick(() => {
                this.activateTopCard();
            });
        },

        // Inicializa Interact + anima entrada do próximo card
        activateTopCard() {
            this.$nextTick(() => {
                const cards = this.$el.querySelectorAll(
                    ".card-item:not(.interact-enabled)",
                );
                if (cards.length === 0) return;

                const top = cards[0];

                // animação de entrada suave
                top.animate(
                    [
                        { transform: "scale(0.95)", opacity: 0 },
                        { transform: "scale(1)", opacity: 1 },
                    ],
                    {
                        duration: 300,
                        easing: "ease-out",
                        fill: "forwards",
                    },
                );

                this.initInteract(top);
            });
        },

        // Inicializa interações
        initInteract(element) {
            if (!element || element.classList.contains("interact-enabled"))
                return;
            element.classList.add("interact-enabled");

            const component = this;

            /******************************************************
             *                       LIKE
             ******************************************************/
            element.addEventListener("dblclick", () => {
                element.style.border = "4px solid #e63946";

                // pulsada
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

                // corações
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
                        heart.remove(),
                    );
                }

                // Salvar curtida no banco (já implementado)
                // Salvar interação no cache
                const vagaId = element.getAttribute("data-vaga-id");
                if (vagaId) {
                    fetch("/vagas/interagir", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document
                                .querySelector('meta[name="csrf-token"]')
                                .getAttribute("content"),
                        },
                        body: JSON.stringify({
                            vaga_id: vagaId,
                            tipo: "curtida",
                        }),
                    });
                }

                const finalizeRemoval = () => {
                    const rise = element.animate(
                        [
                            { transform: "translate(0px, 0px)" },
                            { transform: "translate(0px, -550px)" },
                        ],
                        {
                            duration: 550,
                            easing: "ease-in-out",
                            fill: "forwards",
                        },
                    );

                    rise.onfinish = () => {
                        component.removeTopCard();
                    };
                };

                if (!vagaId) {
                    pulseAnimation.onfinish = finalizeRemoval;
                    return;
                }

                // salvar curtida
                fetch("/vagas/curtir", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute("content"),
                    },
                    body: JSON.stringify({ vaga_id: vagaId }),
                })
                    .then((res) => res.json())
                    .then((data) => {
                        if (data.success) {
                            pulseAnimation.onfinish = finalizeRemoval;
                            if (pulseAnimation.playState === "finished") {
                                finalizeRemoval();
                            }
                        }
                    })
                    .catch(console.error);
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

                    // Sem transição durante o drag
                    element.style.transition = "none";
                    element.style.transform = `translate(${x}px, ${y}px) rotate(${rotation}deg)`;
                    element.setAttribute("data-x", x);
                    element.setAttribute("data-y", y);
                },
                onend(event) {
                    const card = event.target;
                    const vagaId = card.getAttribute("data-vaga-id");
                    const x = parseFloat(card.getAttribute("data-x")) || 0;
                    const direction = x > 0 ? "right" : "left";

                    if (Math.abs(x) > 150) {
                        // Sempre recusar vaga, independente do lado
                        fetch("/vagas/interagir", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": document
                                    .querySelector('meta[name="csrf-token"]')
                                    .getAttribute("content"),
                            },
                            body: JSON.stringify({
                                vaga_id: vagaId,
                                tipo: "recusada",
                            }),
                        }).catch(console.error);
                        card.classList.add(
                            direction === "right"
                                ? "swipe-right"
                                : "swipe-left",
                        );
                        setTimeout(() => {
                            component.removeTopCard();
                        }, 300);
                    } else {
                        card.style.transition = "transform 0.3s ease";
                        card.style.transform = "";
                        card.setAttribute("data-x", 0);
                        card.setAttribute("data-y", 0);
                        setTimeout(() => {
                            card.style.transition = "";
                        }, 300);
                    }
                },
            });
        },
    };
}

function enterpriseFeed() {
    return {
        cards: [],
        isLoading: true,

        async init() {
            await this.fetchFeed();
            // Inicia o watcher igual ao cardStack para garantir que o topo sempre ative
            this.$watch("cards", () => this.activateTopCard());
        },

        async fetchFeed() {
            this.isLoading = true;
            try {
                // Rota específica do enterprise
                const res = await fetch("/enterprise/api/feed");
                if (res.ok) {
                    this.cards = await res.json();
                }
            } catch (e) {
                console.error("Erro feed", e);
            } finally {
                this.isLoading = false;
            }
        },

        // Igual ao cardStack: remove o primeiro e reativa o próximo
        removeTopCard() {
            this.cards = this.cards.slice(1);
            this.$nextTick(() => {
                this.activateTopCard();
            });
        },

        // Igual ao cardStack: animação de entrada scale 0.95 -> 1
        activateTopCard() {
            this.$nextTick(() => {
                const cards = this.$el.querySelectorAll(
                    ".card-item:not(.interact-enabled)",
                );
                if (cards.length === 0) return;

                const top = cards[0];

                top.animate(
                    [
                        { transform: "scale(0.95)", opacity: 0 },
                        { transform: "scale(1)", opacity: 1 },
                    ],
                    {
                        duration: 300,
                        easing: "ease-out",
                        fill: "forwards",
                    },
                );

                this.initInteract(top);
            });
        },

        initInteract(element) {
            if (!element || element.classList.contains("interact-enabled"))
                return;
            element.classList.add("interact-enabled");

            const component = this;

            /******************************************************
             * LIKE (DUPLO CLIQUE)
             * (Copiado exatamente do cardStack: Borda, Corações, Pulso)
             ******************************************************/
            element.addEventListener("dblclick", () => {
                // 1. Borda vermelha
                element.style.border = "4px solid #e63946";

                // 2. Animação de pulso
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

                // 3. Chuva de Corações (Copiado do cardStack)
                for (let i = 0; i < 15; i++) {
                    const heart = document.createElement("div");
                    heart.innerHTML = "❤️";
                    heart.classList.add("floating-heart"); // Certifique-se de ter esse CSS global
                    heart.style.left = `${Math.random() * 100}vw`;
                    heart.style.fontSize = `${Math.random() * 30 + 40}px`;
                    heart.style.animationDuration = `${
                        Math.random() * 1 + 1.5
                    }s`;
                    document.body.appendChild(heart);
                    heart.addEventListener("animationend", () =>
                        heart.remove(),
                    );
                }

                // Dados
                const vagaId = element.getAttribute("data-vaga-id");
                const userId = element.getAttribute("data-user-id");

                // 4. Ação (Adaptado para Enterprise)
                if (typeof window.aprovarCandidato === "function") {
                    window.aprovarCandidato(vagaId, userId);
                }

                // 5. Função de finalizar remoção (Subir e sumir)
                const finalizeRemoval = () => {
                    const rise = element.animate(
                        [
                            { transform: "translate(0px, 0px)" },
                            { transform: "translate(0px, -550px)" },
                        ],
                        {
                            duration: 550,
                            easing: "ease-in-out",
                            fill: "forwards",
                        },
                    );

                    rise.onfinish = () => {
                        component.removeTopCard();
                    };
                };

                // Aguarda o pulso terminar para subir
                pulseAnimation.onfinish = finalizeRemoval;
            });

            /******************************************************
             * DRAG (ARRASTAR)
             * (Mesma física e classes swipe-right/left do cardStack)
             ******************************************************/
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

                    const rotation = x * 0.1; // Rotação igual cardStack

                    element.style.transform = `translate(${x}px, ${y}px) rotate(${rotation}deg)`;
                    element.setAttribute("data-x", x);
                    element.setAttribute("data-y", y);
                },
                onend(event) {
                    const card = event.target;
                    const userId = card.getAttribute("data-user-id");
                    const vagaId = card.getAttribute("data-vaga-id");

                    // Pega o X acumulado (igual cardStack logic)
                    const x = parseFloat(card.getAttribute("data-x")) || 0;
                    const direction = x > 0 ? "right" : "left";

                    // Threshold de 150px
                    if (Math.abs(x) > 150) {
                        if (direction === "right") {
                            // Aprovar
                            if (typeof window.aprovarCandidato === "function") {
                                window.aprovarCandidato(vagaId, userId);
                            }
                        } else {
                            // Rejeitar (Rota Enterprise)
                            fetch("/enterprise/candidatos/rejeitar", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": document.querySelector(
                                        'meta[name="csrf-token"]',
                                    ).content,
                                },
                                body: JSON.stringify({
                                    vaga_id: vagaId,
                                    user_id: userId,
                                }),
                            }).catch(console.error);
                        }

                        // Adiciona a classe CSS exata que o cardStack usa
                        card.classList.add(
                            direction === "right"
                                ? "swipe-right"
                                : "swipe-left",
                        );

                        // Remove depois da animação
                        setTimeout(() => {
                            // Aqui usamos removeTopCard() ao invés de card.remove()
                            // para garantir que o array do Alpine atualize e o próximo card suba
                            component.removeTopCard();
                        }, 300);
                    } else {
                        // Volta pro centro
                        card.style.transition = "transform 0.3s ease"; // Adicionei transição suave no reset
                        card.style.transform = "";
                        card.setAttribute("data-x", 0);
                        card.setAttribute("data-y", 0);
                        setTimeout(() => {
                            card.style.transition = "";
                        }, 300);
                    }
                },
            });
        },
    };
}

window.aprovarCandidato = async function (vagaId, userId) {
    try {
        console.log("Aprovando candidato:", vagaId, userId);

        const csrfTag = document.querySelector('meta[name="csrf-token"]');
        const csrf = csrfTag ? csrfTag.getAttribute("content") : null;
        if (!csrf) {
            window.dispatchEvent(
                new CustomEvent("notify", {
                    detail: {
                        type: "error",
                        title: "Erro",
                        msg: "CSRF token não encontrado.",
                    },
                }),
            );
            return;
        }

        const res = await fetch("/enterprise/candidatos/curtir", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrf,
            },
            body: JSON.stringify({
                user_id: userId,
                vaga_id: vagaId,
            }),
        });

        const json = await res.json();

        if (json && json.success) {
            window.dispatchEvent(
                new CustomEvent("notify", {
                    detail: {
                        type: "success",
                        title: "Aprovado!",
                        msg: json.message || "Candidato aprovado.",
                    },
                }),
            );
        } else {
            window.dispatchEvent(
                new CustomEvent("notify", {
                    detail: {
                        type: "error",
                        title: "Erro",
                        msg:
                            (json && json.message) ||
                            "Falha ao aprovar candidato.",
                    },
                }),
            );
        }
    } catch (e) {
        console.error("Erro ao aprovar candidato:", e);
        window.dispatchEvent(
            new CustomEvent("notify", {
                detail: {
                    type: "error",
                    title: "Erro",
                    msg: "Falha ao aprovar candidato.",
                },
            }),
        );
    }
};

function registrationForm() {
    return {
        step: 1,
        totalSteps: 5, // Ajuste conforme seus steps reais (agora são 2 na modal?)
        errors: {},
        isChecking: {},

        fields: {
            nome_real: "",
            username: "",
            email: "",
            telefone: "",
            datanasc: "",
            cpf: "",
            password: "",
            password_confirmation: "",
        },

        // Lógica de Bloqueio do Botão
        get isStepInvalid() {
            // Se estiver verificando no servidor ou tiver erro -> Bloqueia
            if (Object.values(this.isChecking).some(Boolean)) return true;
            if (Object.values(this.errors).some(Boolean)) return true;

            // Validação de campos vazios (Adapte conforme seus steps reais na View)
            switch (this.step) {
                case 1:
                    return !this.fields.nome_real || !this.fields.username;
                case 2:
                    return !this.fields.email;
                case 3:
                    return !this.fields.telefone;
                case 4:
                    return !this.fields.datanasc || !this.fields.cpf;
                case 5:
                    return (
                        !this.fields.password ||
                        !this.fields.password_confirmation
                    );
                default:
                    return false;
            }
        },

        init() {
            this.$watch("step", () => this.updateProgressBar());
        },

        updateProgressBar() {
            if (this.$refs.progressBar) {
                const percentage = (this.step / this.totalSteps) * 100;
                this.$refs.progressBar.style.width = `${percentage}%`;
            }
        },

        // VALIDAÇÃO COM TOAST
        async validateField(field, type = "user") {
            const value = this.fields[field];

            // 1. Limpa erros anteriores para não ficar vermelho à toa
            // (Exceto se for senha, pois validamos abaixo)
            if (field !== "password" && field !== "password_confirmation") {
                this.errors[field] = "";
            }

            // === NOVA LÓGICA DE SENHA (LOCAL) ===
            if (field === "password" || field === "password_confirmation") {
                // A) Valida tamanho da senha (mínimo 8)
                if (this.fields.password && this.fields.password.length < 8) {
                    this.errors.password = "Mínimo de 8 caracteres.";
                } else {
                    this.errors.password = ""; // Limpa se estiver OK
                }

                // B) Valida se coincidem (só se já começou a digitar a confirmação)
                if (this.fields.password_confirmation) {
                    if (
                        this.fields.password !==
                        this.fields.password_confirmation
                    ) {
                        this.errors.password_confirmation =
                            "As senhas não conferem.";
                    } else {
                        this.errors.password_confirmation = ""; // Limpa se estiver OK
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
                const csrf = document.querySelector(
                    'meta[name="csrf-token"]',
                )?.content;
                const response = await fetch("/api/validate-field", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        Accept: "application/json",
                        "X-CSRF-TOKEN": csrf,
                    },
                    body: JSON.stringify({ field, value, type }),
                });

                const data = await response.json();

                if (!response.ok) {
                    let msg = data.message || "Dado inválido.";
                    this.errors[field] = msg;

                    // Dispara o Toast Vermelho
                    window.dispatchEvent(
                        new CustomEvent("notify", {
                            detail: {
                                type: "danger",
                                title: "Atenção",
                                msg: msg,
                            },
                        }),
                    );
                } else {
                    // Se validou com sucesso na API, garante que limpa o erro
                    this.errors[field] = "";
                }
            } catch (e) {
                console.error(e);
            } finally {
                this.isChecking[field] = false;
            }
        },
    };
}

function enterpriseForm() {
    return {
        step: 1,
        totalSteps: 5,
        errors: {},
        isChecking: {},

        fields: {
            nome_empresa: "",
            ramo: "",
            email: "",
            telefone: "",
            cnpj: "",
            password: "",
            password_confirmation: "",
        },

        get isStepInvalid() {
            if (Object.values(this.isChecking).some(Boolean)) return true;
            if (Object.values(this.errors).some(Boolean)) return true;

            switch (this.step) {
                case 1:
                    return !this.fields.nome_empresa || !this.fields.ramo;
                case 2:
                    return !this.fields.email;
                case 3:
                    return !this.fields.telefone;
                case 4:
                    return !this.fields.cnpj;
                case 5:
                    return (
                        !this.fields.password ||
                        !this.fields.password_confirmation
                    );
                default:
                    return false;
            }
        },

        init() {
            this.$watch("step", () => this.updateProgressBar());
        },

        updateProgressBar() {
            if (this.$refs.progressBar) {
                const percentage = (this.step / this.totalSteps) * 100;
                this.$refs.progressBar.style.width = `${percentage}%`;
            }
        },

        // VALIDAÇÃO COM TOAST (EMPRESA)
        async validateField(field, type = "enterprise") {
            const value = this.fields[field];

            // 1. Limpa erros anteriores para não ficar vermelho à toa
            // (Exceto se for senha, pois validamos abaixo)
            if (field !== "password" && field !== "password_confirmation") {
                this.errors[field] = "";
            }

            // === NOVA LÓGICA DE SENHA (LOCAL) ===
            if (field === "password" || field === "password_confirmation") {
                // A) Valida tamanho da senha (mínimo 8)
                if (this.fields.password && this.fields.password.length < 8) {
                    this.errors.password = "Mínimo de 8 caracteres.";
                } else {
                    this.errors.password = ""; // Limpa se estiver OK
                }

                // B) Valida se coincidem (só se já começou a digitar a confirmação)
                if (this.fields.password_confirmation) {
                    if (
                        this.fields.password !==
                        this.fields.password_confirmation
                    ) {
                        this.errors.password_confirmation =
                            "As senhas não conferem.";
                    } else {
                        this.errors.password_confirmation = ""; // Limpa se estiver OK
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
                const csrf = document.querySelector(
                    'meta[name="csrf-token"]',
                )?.content;
                const response = await fetch("/api/validate-field", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        Accept: "application/json",
                        "X-CSRF-TOKEN": csrf,
                    },
                    body: JSON.stringify({ field, value, type }),
                });

                const data = await response.json();

                if (!response.ok) {
                    let msg = data.message || "Dado inválido.";
                    this.errors[field] = msg;

                    // Dispara o Toast Vermelho
                    window.dispatchEvent(
                        new CustomEvent("notify", {
                            detail: {
                                type: "danger",
                                title: "Atenção",
                                msg: msg,
                            },
                        }),
                    );
                } else {
                    // Se validou com sucesso na API, garante que limpa o erro
                    this.errors[field] = "";
                }
            } catch (e) {
                console.error(e);
            } finally {
                this.isChecking[field] = false;
            }
        },
    };
}

function dashboardView() {
    return {
        featuredJob: null, // Aqui vamos guardar a vaga destaque
        loading: true,

        init() {
            this.fetchFeaturedJob();
        },

        async fetchFeaturedJob() {
            try {
                // Chama a nova rota que criamos
                const response = await fetch("/api/vagas/destaque");

                if (response.ok) {
                    // Se achou vaga (não é null), guarda na variável
                    const data = await response.json();
                    if (data) {
                        this.featuredJob = data;
                    }
                }
            } catch (error) {
                console.error("Erro ao buscar destaque:", error);
            } finally {
                this.loading = false;
            }
        },
    };
}

// Registro Global do Alpine
window.Alpine = Alpine;
window.ApexCharts = ApexCharts;
Alpine.data("enterpriseFeed", enterpriseFeed);
Alpine.data("cardStack", cardStack);
Alpine.data("registrationForm", registrationForm);
Alpine.data("enterpriseForm", enterpriseForm);
Alpine.data("dashboardView", dashboardView);

Alpine.start();

// --- 4. LÓGICA EXECUTADA APÓS O DOM CARREGAR ---
// =================================================================
// COLE ESTE BLOCO INTEIRO NO LUGAR DO SEU "DOMContentLoaded" ATUAL
// =================================================================
document.addEventListener("DOMContentLoaded", () => {
    console.log("DOM Carregado. Executando scripts adicionais.");

    // --- 1. LÓGICA DE TEMA (O SEU CÓDIGO) ---
    function applyThemeFromStorage() {
        const savedTheme = localStorage.getItem("theme") || "light";
        const html = document.documentElement;

        if (savedTheme === "dark") {
            html.classList.add("dark");
        } else {
            html.classList.remove("dark");
        }
    }

    // Aplica imediatamente ao carregar
    applyThemeFromStorage();

    // --- 2. NOVA LÓGICA DE FONTE (ACESSIBILIDADE) ---
    function applyFontFromStorage() {
        // Níveis: 0 = Pequeno, 1 = Normal (Padrão), 2 = Grande, 3 = Extra Grande
        const savedLevel = localStorage.getItem("font-level") || "1";
        const html = document.documentElement;

        // Remove classes anteriores para evitar conflito
        html.classList.remove("text-sm", "text-base", "text-lg", "text-xl");

        // Define o tamanho base no HTML. O Tailwind usa 'rem', então tudo escala junto.
        switch (savedLevel) {
            case "0": // Pequeno
                html.style.fontSize = "14px";
                break;
            case "1": // Normal (Padrão dos navegadores é 16px)
                html.style.fontSize = "16px";
                break;
            case "2": // Grande
                html.style.fontSize = "18px";
                break;
            case "3": // Extra Grande
                html.style.fontSize = "20px";
                break;
            default:
                html.style.fontSize = "16px";
        }
    }

    // Aplica fonte imediatamente
    applyFontFromStorage();

    // --- EVENTOS GLOBAIS ---
    window.addEventListener("pageshow", (event) => {
        if (event.persisted) {
            applyThemeFromStorage();
            applyFontFromStorage(); // Reaplica fonte ao voltar do cache
        }
    });

    // Disponibiliza as funções globalmente para o Alpine usar
    window.applyThemeGlobal = applyThemeFromStorage;
    window.applyFontGlobal = applyFontFromStorage;

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
                'button[data-carousel-slide-to][aria-current="true"]',
            );
            if (activeButton) {
                const activeIndex = activeButton.getAttribute(
                    "data-carousel-slide-to",
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

    // MÓDULO: NOTIFICAÇÕES GLOBAIS (TOAST)
    window.addEventListener("notify", (event) => {
        const { type, title, msg } = event.detail;

        // Cria o toast
        const toast = document.createElement("div");
        toast.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg text-white transition-transform duration-300 transform translate-x-full ${
            type === "success"
                ? "bg-green-500"
                : type === "error" || type === "danger"
                  ? "bg-red-500"
                  : "bg-blue-500"
        }`;
        toast.innerHTML = `
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${
                        type === "success"
                            ? "M5 13l4 4L19 7"
                            : "M6 18L18 6M6 6l12 12"
                    }"></path>
                </svg>
                <div>
                    <div class="font-bold">${title}</div>
                    <div class="text-sm">${msg}</div>
                </div>
            </div>
        `;

        document.body.appendChild(toast);

        // Anima entrada
        setTimeout(() => {
            toast.classList.remove("translate-x-full");
        }, 100);

        // Remove após 3 segundos
        setTimeout(() => {
            toast.classList.add("translate-x-full");
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 3000);
    });

    // MÓDULO: SCALE USER FUNCTION
    window.scaleUser = function (userId, vagaId) {
        // Mostra o modal de confirmação
        const modal = document.getElementById("confirmModal");
        if (modal) {
            modal.classList.remove("hidden");

            // Configura os botões
            document.getElementById("confirmYes").onclick = () => {
                modal.classList.add("hidden");
                performScale(userId, vagaId);
            };
            document.getElementById("confirmNo").onclick = () => {
                modal.classList.add("hidden");
            };
        }
    };

    function performScale(userId, vagaId) {
        fetch("/enterprises/chat/scale", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
            body: JSON.stringify({ user_id: userId, vaga_id: vagaId }),
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    window.dispatchEvent(
                        new CustomEvent("notify", {
                            detail: {
                                type: "success",
                                title: "Sucesso",
                                msg: "Usuário escalado com sucesso!",
                            },
                        }),
                    );
                } else {
                    window.dispatchEvent(
                        new CustomEvent("notify", {
                            detail: {
                                type: "error",
                                title: "Erro",
                                msg:
                                    data.message || "Falha ao escalar usuário.",
                            },
                        }),
                    );
                }
            })
            .catch((error) => {
                console.error("Erro:", error);
                window.dispatchEvent(
                    new CustomEvent("notify", {
                        detail: {
                            type: "error",
                            title: "Erro",
                            msg: "Erro ao escalar usuário.",
                        },
                    }),
                );
            });
    }
});
