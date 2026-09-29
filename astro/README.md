# EcoFlow Landing — Astro + astro-laravel

Landing page EcoFlow dibangun dengan [Astro](https://astro.build/) dan dikompilasi
menjadi Blade biasa memakai adapter [`astro-laravel`](https://www.npmjs.com/package/astro-laravel).

## Struktur

```
astro/
├── src/
│   ├── layouts/LandingLayout.astro   # <html>, fonts, @vite + @livewire Blade hooks
│   ├── pages/welcome.blade.php.astro # halaman landing → resources/views/astro/welcome.blade.php
│   ├── components/                   # Navbar, Hero, About, Steps, Impact, CalculatorSection, ...
│   └── styles/landing.css            # Tailwind v4 + animasi landing
├── astro.config.mjs                  # adapter: installationDir ../ (root Laravel)
└── package.json
```

Halaman memakai ekstensi `.blade.php.astro` supaya adapter men-generate
`resources/views/astro/welcome.blade.php`. Sintaks Blade (`{{ route() }}`,
`@auth`, `<livewire:...>`) ditulis sebagai string Astro (`{"..."}` /
`set:html`) sehingga lolos utuh ke Blade hasil compile.

Aset statis (gambar, favicon) tetap merujuk ke `public/` Laravel
(`public/images/...`), sesuai aturan adapter. CSS/JS hasil build Astro
mendarat di `public/_astro/`.

## Perintah

Jalankan dari folder `astro/`:

```bash
npm install
npm run dev    # Astro di 127.0.0.1:4321, proxy ke Laravel 127.0.0.1:8000
npm run build  # generate resources/views/astro/*.blade.php + public/_astro/
```

Alur dev yang disarankan (dua terminal):

```bash
# terminal 1 — Laravel
php artisan serve --host=127.0.0.1 --port=8000

# terminal 2 — Astro
cd astro && npm run dev
```

Lalu buka halaman lewat URL Laravel (`http://127.0.0.1:8000/`), bukan URL Astro —
adapter mem-proxy request lewat Astro dan me-render Blade (`Blade::render`).

## Route

`routes/web.php`:

```php
Route::view('/', 'astro.welcome')->name('home');
```

File lama dibackup sebagai `resources/views/welcome-legacy.blade.php`.
