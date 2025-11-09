# Setup & Environments

Prerequisites
- PHP 8.2+
- Composer
- MySQL 8 (or 5.7+ with utf8mb4 fix applied in AppServiceProvider)
- Node.js (for assets via Vite)

Local setup
1. composer install
2. cp .env.example .env and configure DB
3. php artisan key:generate
4. php artisan migrate --seed
5. php artisan serve

Testing
- php artisan test
- Filter: php artisan test --filter=CRM

Environments
- DB connection and mail config in config/*.php
- Queue is optional; exports are synchronous in tests (ShouldQueue removed in test context)
