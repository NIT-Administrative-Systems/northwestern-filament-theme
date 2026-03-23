import { defineConfig } from "@playwright/test";

export default defineConfig({
    testDir: "./e2e",
    timeout: 30_000,
    retries: 0,
    use: {
        baseURL: "http://127.0.0.1:8000",
        viewport: { width: 1440, height: 900 },
        screenshot: "on",
    },
    webServer: {
        command: "php artisan serve --port=8000 --no-reload",
        url: "http://127.0.0.1:8000",
        reuseExistingServer: !process.env.CI,
        timeout: 30_000,
    },
});
