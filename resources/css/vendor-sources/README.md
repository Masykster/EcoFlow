# Vendored assets — kenapa folder ini ada?

`vite build` di Vercel berjalan SEBELUM `composer install` (environment build
Vercel tidak punya PHP/Composer; dependency PHP baru di-install belakangan di
dalam builder `vercel-php`, dan `vendor/` memang tidak ikut ter-upload).
Akibatnya `resources/css/app.css` tidak boleh `@import` / `@source` langsung
dari `../../vendor/...` — file-nya tidak ada saat build dan build akan gagal
dengan `Can't resolve ... in resources/css`.

Isi folder ini adalah SALINAN dari package Composer, khusus untuk kebutuhan
build frontend (Tailwind/Vite) — BUKAN untuk runtime PHP (runtime tetap pakai
`vendor/` asli):

- `../vendor/flux.css` — salinan `vendor/livewire/flux/dist/flux.css`
- `flux/` — salinan `vendor/livewire/flux/stubs/**/*.blade.php`
  (dipindai Tailwind via `@source` agar utility class komponen Flux ikut
  ter-generate; file-file ini tidak pernah di-render Laravel)
- `pagination/` — salinan
  `vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php`

## Refresh setelah `composer update`

Jika versi `livewire/flux` atau `laravel/framework` berubah, sinkronkan ulang
dari `vendor/` (dari root repo):

```powershell
Copy-Item vendor\livewire\flux\dist\flux.css resources\css\vendor\flux.css -Force
Copy-Item vendor\livewire\flux\stubs\* resources\css\vendor-sources\flux\ -Recurse -Force
Copy-Item vendor\laravel\framework\src\Illuminate\Pagination\resources\views\*.blade.php resources\css\vendor-sources\pagination\ -Force
```
