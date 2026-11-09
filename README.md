# Laravel Rental Management

Aplikasi manajemen penyewaan barang berbasis Laravel, Livewire, Docker, MySQL, dan Redis.

## Teknologi

- PHP
- Laravel
- Livewire Volt
- Alpine.js
- Tailwind CSS
- MySQL
- Redis
- Docker
- Laravel Sail
- Mailpit
- Pest

## Menjalankan Project

```bash
cp .env.example .env
./vendor/bin/sail up -d
./vendor/bin/sail composer install
./vendor/bin/sail npm install
./vendor/bin/sail npm run build
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan storage:link