import { test, type Page } from "@playwright/test";
import percySnapshot from "@percy/playwright";

const SHOWCASE_URL = "/theme-showcase";

/**
 * Sections to screenshot individually.
 *
 * Each entry maps a human-readable name to the heading text Playwright
 * will use to locate the section on the page. Percy receives one snapshot
 * per section per color scheme (light + dark).
 */
const sections: Array<{ name: string; testId: string }> = [
    { name: "Theme Preview", testId: "theme-preview" },
    { name: "Color Palette", testId: "color-palette" },
    { name: "Typography", testId: "typography" },
    { name: "Buttons", testId: "buttons" },
    { name: "Badges", testId: "badges" },
    { name: "Stats Overview", testId: "stats-overview" },
    { name: "Table Preview", testId: "table-preview" },
    { name: "Sections & Cards", testId: "sections-cards" },
    { name: "Form Components", testId: "form-components" },
    { name: "Infolist / Read-Only Display", testId: "infolist" },
    { name: "Empty State", testId: "empty-state" },
    { name: "Inline Alerts", testId: "inline-alerts" },
    { name: "Loading & Disabled States", testId: "loading-disabled" },
];

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

async function snapshotSection(
    page: Page,
    name: string,
    testId: string,
    scheme: string
) {
    const section = page.locator(`[data-testid="${testId}"]`);

    await section.scrollIntoViewIfNeeded();

    await percySnapshot(page, `${name} [${scheme}]`, {
        scope: `[data-testid="${testId}"]`,
    });
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

for (const { name, testId } of sections) {
    test(`${name} - light`, async ({ page }) => {
        await page.goto(SHOWCASE_URL, { waitUntil: "load" });
        await setColorScheme(page, "light");
        await snapshotSection(page, name, testId, "light");
    });

    test(`${name} - dark`, async ({ page }) => {
        await page.goto(SHOWCASE_URL, { waitUntil: "load" });
        await setColorScheme(page, "dark");
        await snapshotSection(page, name, testId, "dark");
    });
}

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
