import { test as setup } from "@playwright/test";

export const SIGNED_IN = "e2e/.auth/demo.json";

/**
 * Sign in once with the seeded demo account, which the sign-in page fills
 * in, and share the session: Filament allows five attempts a minute.
 */
setup("sign in", async ({ page }) => {
    await page.goto("/login", { waitUntil: "networkidle" });
    await page.getByRole("button", { name: "Sign in" }).click();
    await page.locator(".fi-user-menu-trigger").waitFor();
    await page.context().storageState({ path: SIGNED_IN });
});
