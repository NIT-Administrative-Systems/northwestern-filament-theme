import { test, type Page } from "@playwright/test";
import percySnapshot from "@percy/playwright";

const SHOWCASE_URL = "/theme-showcase";

/**
 * Use Filament's theme switcher buttons to toggle between light and dark mode.
 * The switcher renders three buttons: light, dark, and system.
 */
async function setColorScheme(page: Page, scheme: "light" | "dark") {
    const button = page.locator(
        `.fi-theme-switcher-btn[aria-label*="${scheme}" i]`
    );
    await button.waitFor();
    await button.click();

    if (scheme === "dark") {
        await page.waitForFunction(() =>
            document.documentElement.classList.contains("dark")
        );
    } else {
        await page.waitForFunction(
            () => !document.documentElement.classList.contains("dark")
        );
    }
}

test("full page - light mode", async ({ page }) => {
    await page.goto(SHOWCASE_URL, { waitUntil: "load" });
    await setColorScheme(page, "light");
    await percySnapshot(page, "Theme Showcase [light]", {});
});

test("full page - dark mode", async ({ page }) => {
    await page.goto(SHOWCASE_URL, { waitUntil: "load" });
    await setColorScheme(page, "dark");
    await percySnapshot(page, "Theme Showcase [dark]", {});
});

test("footer - light", async ({ page }) => {
    await page.goto(SHOWCASE_URL, { waitUntil: "load" });
    await setColorScheme(page, "light");

    const footer = page.locator(".nu-footer");
    await footer.scrollIntoViewIfNeeded();
    await percySnapshot(page, "Footer [light]", {
        scope: ".nu-footer",
    });
});

test("footer - dark", async ({ page }) => {
    await page.goto(SHOWCASE_URL, { waitUntil: "load" });
    await setColorScheme(page, "dark");

    const footer = page.locator(".nu-footer");
    await footer.scrollIntoViewIfNeeded();
    await percySnapshot(page, "Footer [dark]", {
        scope: ".nu-footer",
    });
});
