# ConvertPortrait

Portrait-to-landscape video converter. Freemium SaaS — free browser-based processing, Pro ($19.99 lifetime) adds server-side fast processing and premium templates.

## Tech Stack

- **Backend:** Laravel 12, PHP 8.2, PostgreSQL (prod) / SQLite (local)
- **Frontend:** Vue 3 + Inertia.js, Tailwind CSS 4, Vite 7
- **Video Processing:** FFmpeg (server-side), FFmpeg.wasm 0.12 (browser-side)
- **Payments:** Stripe (one-time checkout, webhooks)
- **Deployment:** DigitalOcean App Platform, Docker, Apache

## Project Structure

```
app/Http/Controllers/
  StripeController.php    # Checkout, webhooks, Pro email restore
  VideoController.php     # Server-side FFmpeg processing
app/Models/
  ProUser.php             # Pro user tracking (email, stripe IDs)
resources/js/Pages/
  Home.vue                # Main SPA — all steps (upload, template, processing, done)
routes/web.php            # All routes (Inertia + API endpoints)
config/services.php       # Stripe keys
.do/app.yaml              # DigitalOcean App Platform spec
Dockerfile                # Production build (PHP 8.2 + Apache + FFmpeg + Node)
docker-entrypoint.sh      # Parses DATABASE_URL, runs migrations, caches config
```

## Routes

```
GET  /                  → Home (Inertia)
POST /api/checkout      → Stripe checkout session
POST /api/stripe/webhook → Stripe webhook handler
POST /api/restore       → Email-based Pro status check
POST /api/process       → Server-side video processing (Pro only)
```

## Local Development

```bash
# First time setup
composer setup

# Run dev servers (Laravel + Vite + queue + logs via concurrently)
composer dev
```

Dev server runs at `http://localhost:8000`. Vite dev server handles HMR.

Local uses SQLite at `database/database.sqlite`. No Stripe env vars needed for basic dev (checkout/webhook won't work without them).

### Required env vars for full local dev

```
STRIPE_KEY=pk_test_...
STRIPE_SECRET=sk_test_...
STRIPE_WEBHOOK_SECRET=whsec_...
```

## Deployment (DigitalOcean App Platform)

App is deployed via Docker on DO App Platform. Config lives in `.do/app.yaml`.

### Deploy with doctl

```bash
# Validate the app spec
doctl apps spec validate .do/app.yaml

# First-time create
doctl apps create --spec .do/app.yaml

# Update existing app (get app ID first)
doctl apps list
doctl apps update <app-id> --spec .do/app.yaml

# Force redeploy
doctl apps create-deployment <app-id>

# Check deployment logs
doctl apps logs <app-id> --type=run
doctl apps logs <app-id> --type=build
```

### Deploy via git push

Auto-deploys on push to `main` (`deploy_on_push: true` in app.yaml).

### Environment variables on DO

Set via DO dashboard or `doctl apps update` with the app spec. Critical prod env vars:

- `APP_KEY` — Laravel encryption key (SECRET)
- `DATABASE_URL` — Auto-injected by DO managed DB (`${db.DATABASE_URL}`)
- `STRIPE_KEY`, `STRIPE_SECRET`, `STRIPE_WEBHOOK_SECRET` — Set in DO dashboard as secrets
- `APP_ENV=production`, `APP_DEBUG=false`

### Infrastructure

- **Instance:** `apps-s-1vcpu-1gb` (1 vCPU, 1GB RAM)
- **Database:** DO managed PostgreSQL (dev tier, `production: false`)
- **Region:** NYC
- **Domains:** convertportrait.com + www.convertportrait.com
- **Port:** 8080 (Apache inside Docker)

### How the Docker build works

1. Installs PHP 8.2 + Apache + FFmpeg + Node 20
2. `composer install` and `npm ci` (cached layers)
3. `npm run build` (Vite frontend build)
4. On startup, `docker-entrypoint.sh`:
   - Parses `DATABASE_URL` into `DB_HOST`, `DB_PORT`, etc.
   - Runs `php artisan migrate --force`
   - Caches config, routes, views
   - Starts Apache

## Key Architecture Decisions

- **Single-page app:** One Vue component (`Home.vue`) handles all steps. All state is local refs.
- **No auth system:** Pro status stored by email in `pro_users` table, validated client-side via localStorage (`convertportrait_pro` key) and server-side via `X-Pro-Email` header.
- **Two processing modes:**
  - Browser (free): FFmpeg.wasm runs in browser, video never leaves device
  - Server (Pro): Video uploaded to `/api/process`, FFmpeg runs natively, result streamed back, file deleted
- **CORS headers required:** FFmpeg.wasm needs `Cross-Origin-Opener-Policy: same-origin` and `Cross-Origin-Embedder-Policy: credentialless`. Set in Apache config and Vite dev server.
- **Stripe webhook flow:** `checkout.session.completed` → save email to `pro_users` → user enters email on redirect → `/api/restore` confirms Pro status → saved to localStorage.

## Common Tasks

### Add a new background template

1. Add template object to `templates` array in `Home.vue` (id, name, description, pro flag)
2. Add canvas preview drawing in `drawPreview()` function
3. Add FFmpeg filter string in `buildFilter()` (browser-side, in Home.vue)
4. Add FFmpeg filter in `VideoController::buildFilter()` (server-side)

### Change pricing

- `StripeController::createCheckout()` — `unit_amount` in cents (1999 = $19.99)
- Update copy in upgrade modal in `Home.vue`

### Database changes

```bash
php artisan make:migration <name>
php artisan migrate          # local
# Production: runs automatically on deploy via docker-entrypoint.sh
```

## Gotchas

- The `VerifyCsrfToken` middleware exempts `/api/stripe/webhook` (check `app/Http/Middleware` or `bootstrap/app.php`)
- Server processing timeout is 300s (`VideoController` — `$ffmpeg->setTimeout(300)`)
- FFmpeg.wasm loads from unpkg CDN (`https://unpkg.com/@ffmpeg/core@0.12.6/dist/esm`)
- Pro status check on server uses `X-Pro-Email` header — no session/token auth
- DO managed DB requires SSL (`DB_SSLMODE=require`, set in entrypoint)
