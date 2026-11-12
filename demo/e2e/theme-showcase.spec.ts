import { expect, test, type Page } from "@playwright/test";
import percySnapshot from "@percy/playwright";

const SHOWCASE_URL = "/theme-showcase";

/** The widths the top bar has to fit, from a small phone to a desktop. */
const TOPBAR_WIDTHS = [320, 384, 768, 1024, 1440];

/**
 * Set Filament's stored theme before the page loads. Filament 5
 * reads `filament:theme:mode`, and Filament 4 reads `theme`.
 */
async function goto(page: Page, url: string, scheme: "light" | "dark") {
    await page.addInitScript((value) => {
        localStorage.setItem("filament:theme:mode", value);
        localStorage.setItem("theme", value);
    }, scheme);
    await page.goto(url, { waitUntil: "load" });

    if (scheme === "dark") {
        await page.waitForFunction(() =>
            document.documentElement.classList.contains("dark")
        );
    }
}

for (const scheme of ["light", "dark"] as const) {
    test(`full page - ${scheme} mode`, async ({ page }) => {
        await goto(page, SHOWCASE_URL, scheme);
        await percySnapshot(page, `Theme Showcase [${scheme}]`, {});
    });

    test(`footer - ${scheme}`, async ({ page }) => {
        await goto(page, SHOWCASE_URL, scheme);

        const footer = page.locator(".nu-footer");
        await footer.scrollIntoViewIfNeeded();
        await percySnapshot(page, `Footer [${scheme}]`, {
            scope: ".nu-footer",
        });
    });

    test(`top bar - ${scheme}`, async ({ page }) => {
        await goto(page, "/people", scheme);
        await percySnapshot(page, `Top Bar [${scheme}]`, {
            scope: ".fi-topbar",
            widths: TOPBAR_WIDTHS,
        });
    });

    // A role badge in a column and an entry marked ->wrap().
    test(`wrapped badges - ${scheme}`, async ({ page }) => {
        await goto(page, "/people", scheme);
        await percySnapshot(page, `People [${scheme}]`, {
            widths: [375, 1440],
        });

        await goto(page, "/people/2", scheme);
        await percySnapshot(page, `Person [${scheme}]`, {
            widths: [375, 1440],
        });
    });

    test.describe("signed out", () => {
        test.use({ storageState: { cookies: [], origins: [] } });

        test(`simple page - ${scheme}`, async ({ page }) => {
            await goto(page, "/login", scheme);
            await percySnapshot(page, `Login [${scheme}]`, {
                widths: [375, 1440],
            });
        });
    });
}

/*
 * Percy shows how the top bar looks. These check that it fits: nothing
 * past the right edge, and nothing over the wordmark or its neighbor.
 */
for (const width of TOPBAR_WIDTHS) {
    test(`top bar fits at ${width}px`, async ({ page }) => {
        await page.setViewportSize({ width, height: 900 });
        await page.goto("/people", { waitUntil: "load" });
        // The brand fonts are wider than the fallbacks, so wait for them.
        await page.evaluate(() => document.fonts.ready);

        const layout = await page.evaluate(() => {
            const parts = [
                ".fi-topbar-open-sidebar-btn",
                ".fi-topbar-start .fi-logo",
                ".fi-topbar .nu-env-indicator",
                ".fi-topbar .fi-global-search-field .fi-input-wrp",
                "[data-testid=help-menu-trigger]",
                ".fi-topbar-database-notifications-btn",
                ".fi-topbar .fi-user-menu-trigger",
            ]
                .map((selector) => document.querySelector(selector))
                .filter((el): el is Element => el !== null)
                .map((el) => el.getBoundingClientRect())
                .filter((box) => box.width > 0);

            return {
                pageWidth: document.documentElement.scrollWidth,
                rights: parts.map((box) => Math.round(box.right)),
                overlaps: parts
                    .slice(1)
                    .map((box, i) => Math.round(parts[i].right - box.left))
                    .filter((overlap) => overlap > 0),
            };
        });

        expect(layout.pageWidth).toBeLessThanOrEqual(width);
        expect(Math.max(...layout.rights)).toBeLessThanOrEqual(width);
        expect(layout.overlaps).toEqual([]);
    });
}
