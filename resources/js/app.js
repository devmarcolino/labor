import './bootstrap';
import 'preline';

import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

import { initPreline } from 'preline'

document.addEventListener('DOMContentLoaded', () => {
  initPreline()
})

document.addEventListener("DOMContentLoaded", function () {
    const titleElement = document.getElementById("carousel-title");

    const texts = [
        "A oportunidade bem na sua mão.",
        "As vagas que vem até você.",
        "Gostou da vaga? O trampo é seu."
    ];

    // Pegue os indicadores pelos IDs ou pela classe
    const indicators = [
        document.getElementById("indicator-0"),
        document.getElementById("indicator-1"),
        document.getElementById("indicator-2")
    ];

    let index = 0;

    function updateUI() {
        // Texto com transição
        titleElement.classList.add("opacity-0");
        setTimeout(() => {
            titleElement.textContent = texts[index];
            titleElement.classList.remove("opacity-0");
        }, 300);

        // Indicadores: ativa o atual e desativa os outros
        indicators.forEach((dot, i) => {
            if (i === index) {
                dot.classList.add("bg-sky-600", "border-sky-600", "scale-125");
                dot.classList.remove("border-gray-400");
            } else {
                dot.classList.remove("bg-sky-600", "scale-125");
                dot.classList.add("border-gray-400");
            }
        });
    }

    updateUI();

    setInterval(() => {
        index = (index + 1) % texts.length;
        updateUI();
    }, 5000);
});

document.addEventListener("DOMContentLoaded", () => {
  const themeToggleBtn = document.getElementById("theme-toggle");
  const iconSun = document.getElementById("icon-sun");
  const iconMoon = document.getElementById("icon-moon");
  const html = document.documentElement;

  function setTheme(theme) {
    if (theme === "dark") {
      html.classList.add("dark");
      iconSun.classList.remove("hidden");
      iconMoon.classList.add("hidden");
    } else {
      html.classList.remove("dark");
      iconSun.classList.add("hidden");
      iconMoon.classList.remove("hidden");
    }
    localStorage.setItem("theme", theme);
  }

  // Detecta o tema salvo ou preferencia do sistema
  const savedTheme = localStorage.getItem("theme");
  if (savedTheme) {
    setTheme(savedTheme);
  } else {
    const prefersDark = window.matchMedia("(prefers-color-scheme: dark)").matches;
    setTheme(prefersDark ? "dark" : "light");
  }

  themeToggleBtn.addEventListener("click", () => {
    if (html.classList.contains("dark")) {
      setTheme("light");
    } else {
      setTheme("dark");
    }
  });
});
