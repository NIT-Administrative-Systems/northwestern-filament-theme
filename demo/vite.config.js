import path from "node:path";
import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    resolve: {
        alias: {
            "nu-theme": path.resolve(__dirname, "../dist/theme.css"),
        },
    },
    plugins: [
        tailwindcss(),
        laravel({
            input: ["resources/css/filament/demo/theme.css"],
            refresh: true,
        }),
    ],
});
