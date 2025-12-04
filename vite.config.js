import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import legacy from "@vitejs/plugin-legacy";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),

        legacy({
            // 2. Adicione o plugin aqui
            targets: ["defaults", "not IE 11"],
        }),
    ],
    server: {
        host: "10.168.69.123",
        port: 5173,
    },
});
