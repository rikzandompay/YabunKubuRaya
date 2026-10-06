import { defineConfig, devices } from '@playwright/test';

export default defineConfig({
  testDir: './tests/e2e',
  fullyParallel: false,
  forbidOnly: !!process.env.CI,
  retries: 0,
  workers: 1, // Single worker hemat RAM dan aman untuk SQLite
  reporter: [
    ['list'],
  ],
  use: {
    baseURL: 'http://127.0.0.1:8000',
    trace: 'off',
    video: 'off',
    screenshot: 'only-on-failure',
    permissions: ['clipboard-read', 'clipboard-write'],
    headless: !!process.env.CI || process.env.HEADLESS === 'true' ? true : false, // Ditampilkan langsung di layar desktop (1 browser tunggal)
    launchOptions: {
      slowMo: (!!process.env.CI || process.env.HEADLESS === 'true') ? 0 : 150, // Jeda halus agar pergerakan terlihat jelas di layar
      args: ['--disable-dev-shm-usage', '--no-default-browser-check'],
    },
  },
  projects: [
    {
      name: 'chromium',
      use: {
        ...devices['Desktop Chrome'],
      },
    },
  ],
  webServer: {
    command: 'php artisan serve --port=8000 --no-reload',
    url: 'http://127.0.0.1:8000',
    reuseExistingServer: true,
    timeout: 60 * 1000,
  },
});
