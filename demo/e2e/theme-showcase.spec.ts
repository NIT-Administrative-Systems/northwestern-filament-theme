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
const sections = [
    "Color Palette",
    "Typography",
    "Buttons",
    "Table Preview",
    "Sections & Cards",
    "Inline Alerts",
    "Form Components",
    "Loading & Disabled States",
];

/**
 * Use Filament's theme switcher buttons to toggle between light and dark mode.
 * The switcher renders three buttons: light, dark, and system.
 */
async function setColorScheme(page: Page, scheme: "light" | "dark") {
    const button = page.locator(
        `.fi-theme-switcher-btn[aria-label*="${scheme}" i]`
    );
    await button.click();
    await page.waitForTimeout(300);
}

async function snapshotSection(
    page: Page,
    sectionHeading: string,
    scheme: string
) {
    const section = page
        .locator("section, [class*='fi-section']")
        .filter({ hasText: sectionHeading })
        .first();

    await section.scrollIntoViewIfNeeded();
    await page.waitForTimeout(200);

    await percySnapshot(page, `${sectionHeading} [${scheme}]`, {
        scope: await section.evaluate((el) => {
            el.setAttribute("data-percy-scope", "true");
            return "[data-percy-scope]";
        }),
    });

    await section.evaluate((el) => el.removeAttribute("data-percy-scope"));
}

test("full page - light mode", async ({ page }) => {
    await page.goto(SHOWCASE_URL, { waitUntil: "networkidle" });
    await setColorScheme(page, "light");
    await percySnapshot(page, "Theme Showcase [light]", {});
});

test("full page - dark mode", async ({ page }) => {
    await page.goto(SHOWCASE_URL, { waitUntil: "networkidle" });
    await setColorScheme(page, "dark");
    await percySnapshot(page, "Theme Showcase [dark]", {});
});

for (const section of sections) {
    test(`${section} - light`, async ({ page }) => {
        await page.goto(SHOWCASE_URL, { waitUntil: "networkidle" });
        await setColorScheme(page, "light");
        await snapshotSection(page, section, "light");
    });

    test(`${section} - dark`, async ({ page }) => {
        await page.goto(SHOWCASE_URL, { waitUntil: "networkidle" });
        await setColorScheme(page, "dark");
        await snapshotSection(page, section, "dark");
    });
}

test("footer - light", async ({ page }) => {
    await page.goto(SHOWCASE_URL, { waitUntil: "networkidle" });
    await setColorScheme(page, "light");

    const footer = page.locator(".nu-footer");
    await footer.scrollIntoViewIfNeeded();
    await page.waitForTimeout(200);

    await percySnapshot(page, "Footer [light]", {
        scope: ".nu-footer",
    });
});

test("footer - dark", async ({ page }) => {
    await page.goto(SHOWCASE_URL, { waitUntil: "networkidle" });
    await setColorScheme(page, "dark");

    const footer = page.locator(".nu-footer");
    await footer.scrollIntoViewIfNeeded();
    await page.waitForTimeout(200);

    await percySnapshot(page, "Footer [dark]", {
        scope: ".nu-footer",
    });
});
