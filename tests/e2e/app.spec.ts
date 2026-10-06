import { test, expect, Page } from '@playwright/test';

// Jalankan semua pengujian secara serial dalam 1 browser & 1 tab tunggal tanpa buka-tutup
test.describe.configure({ mode: 'serial' });

let page: Page;

test.beforeAll(async ({ browser }) => {
  // Buka 1 jendela browser tunggal untuk seluruh rangkaian tes
  page = await browser.newPage();
});

test.afterAll(async () => {
  // Tutup jendela browser 1 kali saja setelah semua tes selesai
  if (page) {
    await page.close();
  }
});

test.describe('E2E Test Suite - 1 Browser Tunggal', () => {

  // ==========================================
  // 1. PUBLIC VISITOR JOURNEYS
  // ==========================================

  test('CUJ-PUB-01: Landing Page & Navigasi Utama - Happy Path & Copy Toast', async () => {
    await page.goto('/');

    await expect(page).toHaveTitle(/Yayasan Bakti Umat Nusantara/i);
    await expect(page.getByRole('heading', { level: 1 })).toBeVisible();

    const navbar = page.locator('#navbar');
    await expect(navbar.getByRole('link', { name: 'Home' })).toBeVisible();
    await expect(navbar.getByRole('link', { name: 'Katalog' })).toBeVisible();
    await expect(navbar.getByRole('link', { name: 'Artikel Program' })).toBeVisible();
    await expect(navbar.getByRole('link', { name: 'Galeri' })).toBeVisible();

    await expect(page.getByText(/Visi/i).first()).toBeVisible();
    await expect(page.getByText(/Misi/i).first()).toBeVisible();

    const copyButton = page.getByRole('button', { name: /Salin Nomor|Salin/i }).first();
    if (await copyButton.isVisible()) {
      await copyButton.click();
      const toast = page.locator('#copy-toast');
      await expect(toast).toBeVisible();
    }
  });

  test('CUJ-PUB-02: Katalog Program Yayasan - Happy Path & Program Aktif', async () => {
    await page.goto('/');

    const katalogSection = page.locator('#katalog, section:has-text("Katalog")').first();
    await expect(katalogSection).toBeVisible();

    await expect(page.getByText(/Sedekah Pangan|Jumat Berkah|Tahsin/i).first()).toBeVisible();
  });

  test('CUJ-PUB-03: Baca Artikel & Detail Artikel - Happy Path', async () => {
    await page.goto('/artikel');

    await expect(page).toHaveTitle(/Artikel/i);
    await expect(page.getByRole('heading', { name: /Artikel/i }).first()).toBeVisible();

    const firstArticleLink = page.locator('a[href*="/artikel/"]').first();
    await expect(firstArticleLink).toBeVisible();

    const targetUrl = await firstArticleLink.getAttribute('href');
    await firstArticleLink.click();

    await expect(page).toHaveURL(new RegExp(targetUrl || '/artikel/'));
    await expect(page.getByRole('heading', { level: 1 })).toBeVisible();
  });

  test('CUJ-PUB-03: Detail Artikel - Failure State (Slug Invalid 404)', async () => {
    const response = await page.goto('/artikel/slug-artikel-ini-pasti-tidak-ada-404-not-found');

    expect(response?.status()).toBe(404);
    await expect(page.locator('body')).not.toContainText('Fatal error');
    await expect(page.locator('body')).not.toContainText('QueryException');
  });

  test('CUJ-PUB-04: Galeri Dokumentasi Kegiatan - Happy Path & Filter', async () => {
    await page.goto('/galeri');

    await expect(page).toHaveTitle(/Galeri/i);
    await expect(page.getByRole('heading', { name: /Galeri/i }).first()).toBeVisible();

    const galleryImage = page.locator('img').first();
    await expect(galleryImage).toBeVisible();
  });

  // ==========================================
  // 2. SECURITY & ACCESS CONTROL JOURNEYS (GUEST)
  // ==========================================

  const protectedRoutes = [
    { name: 'Dashboard', path: '/admin/dashboard' },
    { name: 'Keuangan', path: '/admin/keuangan' },
    { name: 'Donatur', path: '/admin/donatur' },
    { name: 'Artikel', path: '/admin/artikel' },
    { name: 'Galeri', path: '/admin/galeri' },
    { name: 'Setting', path: '/admin/setting' },
  ];

  for (const route of protectedRoutes) {
    test(`CUJ-AUTH-01: Guest dilarang mengakses ${route.name} (${route.path}) dan diredirect ke login`, async () => {
      await page.goto(route.path);

      await expect(page).toHaveURL(/.*\/admin\/login/);
      await expect(page.locator('#data\\.email')).toBeVisible();
    });
  }

  test('CUJ-AUTH-01: Login Admin - Failure State (Kredensial Salah)', async () => {
    await page.goto('/admin/login');

    await page.locator('#data\\.email').fill('admin@example.com');
    await page.locator('#data\\.password').fill('password_salah_total_123');

    await page.locator('button[type="submit"]').click();

    await expect(page).toHaveURL(/.*\/admin\/login/);
    await expect(page.getByRole('heading', { name: /Assalamu'alaikum/i })).not.toBeVisible();
  });

  test('CUJ-AUTH-01: Login Admin - Failure State (Email Tidak Valid)', async () => {
    await page.goto('/admin/login');

    const emailInput = page.locator('#data\\.email');
    await emailInput.fill('bukan-format-email');
    await page.locator('#data\\.password').fill('password');

    await page.locator('button[type="submit"]').click();

    const isValid = await emailInput.evaluate((el: HTMLInputElement) => el.checkValidity());
    expect(isValid).toBe(false);
  });

  // ==========================================
  // 3. ADMIN AUTHENTICATION (MASUK SEKALI)
  // ==========================================

  test('CUJ-AUTH-01: Login Admin - Happy Path (Autentikasi Sukses)', async () => {
    await page.goto('/admin/login', { waitUntil: 'domcontentloaded' });

    await page.locator('#data\\.email').fill('admin@example.com');
    await page.locator('#data\\.password').fill('password');
    await page.locator('button[type="submit"]').click();

    // Redirect bisa ke dashboard atau intended URL sebelumnya
    await expect(page).toHaveURL(/.*\/admin\/(dashboard|setting)/, { timeout: 15000 });
    
    // Buka dashboard untuk verifikasi tampilan selamat datang
    await page.goto('/admin/dashboard');
    await expect(page.getByRole('heading', { name: /Assalamu'alaikum/i })).toBeVisible();
  });

  // ==========================================
  // 4. ADMIN BACKOFFICE CRUD OPERATIONS
  // ==========================================

  test('CUJ-ADM-01: Manajemen Donatur - Happy Path & Tambah Donatur', async () => {
    await page.goto('/admin/donatur');

    await expect(page).toHaveTitle(/Manajemen Donatur/i);
    await expect(page.getByRole('heading', { name: /Manajemen Donatur/i })).toBeVisible();

    await page.getByRole('button', { name: /Tambah Donatur Baru/i }).click();

    const form = page.locator('form[action$="/admin/donatur"][method="POST"]');
    const testDonorName = `Donatur E2E ${Date.now()}`;
    await form.locator('input[name="nama_donatur"]').fill(testDonorName);
    await form.locator('select[name="tipe_donatur"]').selectOption('individu');
    await form.locator('input[name="nomor_hp"]').fill('081234567899');
    await form.locator('select[name="kategori"]').selectOption('donasi_bantuan');
    await form.locator('input[placeholder*="Rp 50.000"]').fill('100000');

    await form.locator('button[type="submit"]').click();

    await expect(page.getByText(/Data donatur dan donasi berhasil ditambahkan/i)).toBeVisible({ timeout: 10000 });
    await expect(page.getByRole('cell', { name: testDonorName, exact: true })).toBeVisible();
  });

  test('CUJ-ADM-01: Manajemen Donatur - Failure State (Nama Kosong)', async () => {
    await page.goto('/admin/donatur');

    await page.getByRole('button', { name: /Tambah Donatur Baru/i }).click();

    const form = page.locator('form[action$="/admin/donatur"][method="POST"]');
    const nameInput = form.locator('input[name="nama_donatur"]');
    await nameInput.fill('');

    const isRequired = await nameInput.getAttribute('required');
    expect(isRequired).not.toBeNull();
  });

  test('CUJ-ADM-02: Manajemen Keuangan - Happy Path & Catat Transaksi', async () => {
    await page.goto('/admin/keuangan');

    await expect(page).toHaveTitle(/Manajemen Keuangan/i);
    await expect(page.getByRole('heading', { name: /Manajemen Keuangan/i })).toBeVisible();

    await page.evaluate(() => window.dispatchEvent(new CustomEvent('open-modal-transaksi')));

    const form = page.locator('form#form-catat-transaksi');
    await form.locator('select#type').selectOption('pemasukan');
    await form.locator('select#category').selectOption('jumat_berkah');
    await form.locator('select#donor_id').selectOption({ index: 1 });
    await form.locator('input#amount').fill('250000');
    await form.locator('input#transaction_date').fill(new Date().toISOString().split('T')[0]);
    await form.locator('textarea#description').fill(`Infaq E2E Test ${Date.now()}`);

    await form.locator('button[type="submit"]').click();

    await expect(page.getByText(/berhasil/i)).toBeVisible({ timeout: 10000 });
  });

  test('CUJ-ADM-02: Manajemen Keuangan - Failure State (Nominal Kosong)', async () => {
    await page.goto('/admin/keuangan');

    await page.evaluate(() => window.dispatchEvent(new CustomEvent('open-modal-transaksi')));

    const form = page.locator('form#form-catat-transaksi');
    const amountInput = form.locator('input#amount');
    await amountInput.fill('');

    const isRequired = await amountInput.getAttribute('required');
    expect(isRequired).not.toBeNull();
  });

  test('CUJ-ADM-03: Publikasi Artikel - Happy Path & Filter Kategori', async () => {
    await page.goto('/admin/artikel');

    await expect(page).toHaveTitle(/Artikel/i);
    await expect(page.getByRole('heading', { name: /Artikel/i }).first()).toBeVisible();

    await expect(page.locator('table').first()).toBeVisible();
  });

  test('CUJ-ADM-04: Manajemen Galeri Foto - Happy Path', async () => {
    await page.goto('/admin/galeri');

    await expect(page).toHaveTitle(/Kegiatan Foto|Galeri/i);
    await expect(page.getByRole('heading', { name: /Kegiatan Foto|Galeri/i }).first()).toBeVisible();
  });

  test('CUJ-ADM-05: Pengaturan Sistem & Rekening Bank - Happy Path', async () => {
    await page.goto('/admin/setting');

    await expect(page).toHaveTitle(/Setting|Pengaturan/i);
    await expect(page.getByRole('heading', { name: /Setting|Pengaturan/i }).first()).toBeVisible();

    await expect(page.getByText(/Bank Syariah Indonesia|BSI|Rekening/i).first()).toBeVisible();
  });

});
