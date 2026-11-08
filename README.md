# Material Pass

Greenfield Laravel application for Material Pass, scaffolded with Vue (Inertia), PostgreSQL 16, Redis, and Docker Compose.

## Stack

- PHP 8.3 + Laravel (latest stable)
- Vue 3 via Laravel Breeze / Inertia + Vite
- PostgreSQL 16
- Redis (cache, queue, session)
- Docker & Docker Compose

## Prerequisites

- Docker Engine
- Docker Compose v2

## Quick start

```bash
cp .env.example .env
docker compose up --build -d
```

On first boot the PHP containers install Composer dependencies (if needed) and generate `APP_KEY` when missing.

### Migrate the database

```bash
docker compose exec app php artisan migrate
```

### Open the app

- App (nginx): [http://localhost:8000](http://localhost:8000)
- Vite HMR: [http://localhost:5173](http://localhost:5173)

The home page is a Vue/Inertia welcome screen you can click to confirm Vue reactivity.

## Services

| Service    | Role                                      | Host port (default) |
|------------|-------------------------------------------|---------------------|
| `nginx`    | HTTP front door                           | `8000`              |
| `app`      | PHP-FPM 8.3                               | —                   |
| `postgres` | PostgreSQL 16                             | `5432`              |
| `redis`    | Cache / queue / session                   | `6379`              |
| `queue`    | `php artisan queue:work redis`            | —                   |
| `vite`     | Frontend Vite dev server                  | `5173`              |

Override ports with `APP_PORT`, `DB_PORT_HOST`, `REDIS_PORT_HOST`, and `VITE_PORT` in `.env`.

## Environment

`.env.example` is wired for Compose networking:

- `DB_CONNECTION=pgsql`, host `postgres`
- `REDIS_HOST=redis`, `REDIS_CLIENT=phpredis`
- `CACHE_STORE=redis`
- `QUEUE_CONNECTION=redis`
- `SESSION_DRIVER=redis`

Default database credentials (change for anything beyond local):

- Database / user: `material_pass`
- Password: `secret`

## Queue worker

The `queue` service runs automatically with Compose:

```bash
docker compose up -d queue
```

Manual alternative (same image as `app`):

```bash
docker compose exec app php artisan queue:work redis --sleep=3 --tries=3
```

Use Redis-backed queues for notifications and PDF jobs later.

## Common commands

```bash
# Application shell
docker compose exec app bash

# Composer / Artisan
docker compose exec app composer install
docker compose exec app php artisan migrate
docker compose exec app php artisan tinker

# Frontend (already running in the vite service)
docker compose logs -f vite
docker compose exec vite npm run build
```

## Stopping

```bash
docker compose down
```

Add `-v` only if you also want to wipe Postgres/Redis volumes.
