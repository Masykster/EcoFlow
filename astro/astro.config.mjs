// @ts-check
import { defineConfig, passthroughImageService } from "astro/config";
import laravel from "astro-laravel";
import tailwindcss from "@tailwindcss/vite";

// https://astro.build/config
// Astro project lives in <laravel-root>/astro/, so installationDir points one level up.
export default defineConfig({
    server: {
        host: "127.0.0.1",
        port: 4321,
    },
    adapter: laravel({
        installationDir: "../",
        devProxyTarget: "http://127.0.0.1:8000",
        viewsDirPath: "./resources/views/astro/",
        publicDirPath: "./public/",
    }),
    image: {
        service: passthroughImageService(),
    },
    vite: {
        plugins: [tailwindcss()],
    },
});
