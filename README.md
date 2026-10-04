# EcoFlow

Turns financial activity into carbon insights, with a guest calculator, auth dashboard, and history.

EcoStep is the calculator / dashboard / history feature inside EcoFlow that estimates CO2e — not a separate product.

## Features

- Guest carbon calculator (no login, DB-free)
- Auth dashboard with total emissions, trend chart, and green nudges
- Calculator, history, and achievements pages (`calculator`, `history`, `achievements`)
- Public landing page generated from Astro into a Laravel Blade view
- Google OAuth login + JWT API (`tymon/jwt-auth`)
- Vercel-ready serverless Laravel (`api/index.php`)

## Tech Stack

- Laravel 13 + Livewire 4 + Flux (`composer.json`)
- Astro 6 + `astro-laravel` adapter (`astro/package.json`, `astro/astro.config.mjs`)
- Tailwind CSS 4 + Vite (`package.json`, `vite.config.js`)
- SQLite by default (`.env.example`, `vercel.json`)
- Pest + Pint for test/lint (`composer.json`)

## Getting Started

Prerequisites: PHP ^8.3 + Composer, Node LTS + npm, SQLite.

```bash
composer setup
# install + key:generate + migrate + npm install + npm run build + build:astro
```

Run locally (two terminals):

```bash
# terminal 1 — Laravel + queue + Vite
composer dev

# terminal 2 — Astro
cd astro && npm run dev
# Astro on 127.0.0.1:4321, proxied to Laravel 127.0.0.1:8000
```

Open via Laravel, not Astro directly:

```text
http://127.0.0.1:8000/
```

Useful commands:

```bash
php artisan serve --host=127.0.0.1 --port=8000
npm run build            # Vite build
npm run build:astro      # generate Blade + public/_astro/
composer test            # pint --test + artisan test
```

## Astro Landing Workflow

Source lives in `astro/src/`:

```text
astro/src/
├── layouts/LandingLayout.astro
├── pages/welcome.blade.php.astro  # -> resources/views/astro/welcome.blade.php
├── components/                    # Navbar, Hero, About, Steps, Impact, ...
└── styles/landing.css
```

Adapter config (`astro/astro.config.mjs`): `installationDir: "../"`, `viewsDirPath: "./resources/views/astro/"`, `publicDirPath: "./public/"`, dev proxy `http://127.0.0.1:8000`.

Blade syntax (`{{ route() }}`, `@auth`, `<livewire:...>`) is written as Astro strings so it passes through to the generated Blade untouched. Static assets stay in Laravel `public/` (`public/images/...`); Astro build output lands in `public/_astro/`.

Route (`routes/web.php`):

```php
Route::view('/', 'astro.welcome')->name('home');
```

Old Blade kept as `resources/views/welcome-legacy.blade.php`. Rebuild with `npm run build:astro`. See `astro/README.md` for details.

Auth pages:

```php
Route::view('dashboard', 'dashboard');
Route::view('calculator', 'pages.calculator');
Route::view('history', 'pages.history');
Route::view('achievements', 'pages.achievements');
```

## API

- Google OAuth: `auth/google/redirect` → `auth/google/callback` (`routes/web.php`)
- JWT via `tymon/jwt-auth` for API clients
- Collection: `EcoStep_API.postman_collection.json` (import into Postman, set base URL + token)

## Deployment (Vercel)

`vercel.json`:

- `buildCommand: npm run build && npm run build:astro`
- `outputDirectory: public`
- `functions: api/*.php` on `vercel-php@0.9.0`
- `routes`: filesystem first, then `/(.*)` → `/api/index.php`
- Serverless-safe env: `SESSION_DRIVER=cookie`, `CACHE_STORE=array`, `LOG_CHANNEL=stderr`, `QUEUE_CONNECTION=sync`, `DB_CONNECTION=sqlite`, `DB_DATABASE=/tmp/database.sqlite`, `VIEW_COMPILED_PATH=/tmp`

No extra step — push and Vercel builds Vite + Astro then serves Laravel from `api/index.php`.

## Project Structure

```text
├── app/                    # Livewire, Actions, Services, Models
├── routes/web.php          # /, dashboard, calculator, history, achievements, google auth
├── resources/views/
│   ├── astro/welcome.blade.php   # generated — do not edit, rebuild via build:astro
│   ├── pages/                    # calculator, history, achievements
│   ├── dashboard.blade.php
│   └── welcome-legacy.blade.php  # pre-Astro backup
├── astro/src/              # landing source (layouts, pages, components, styles)
├── public/                 # Laravel public + Astro output in public/_astro/
├── api/index.php           # Vercel serverless entry
├── database/               # migrations, sqlite
├── tests/                  # Pest
└── DESIGN.md               # EcoStep design tokens (Forest Green #1E3F35, Bento grid)
```

## License

MIT per `composer.json:10` — no `LICENSE` file in the repo yet.
