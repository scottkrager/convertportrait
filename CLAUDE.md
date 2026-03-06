# ConvertPortrait

Portrait-to-landscape video converter. Freemium SaaS — free browser-based processing, Pro ($19.99 lifetime) adds server-side fast processing and premium templates.

## Tech Stack

- **Backend:** Laravel 12, PHP 8.2, PostgreSQL (prod) / SQLite (local)
- **Frontend:** Vue 3 + Inertia.js, Tailwind CSS 4, Vite 7
- **Video Processing:** AWS Lambda + FFmpeg (Pro server-side), FFmpeg.wasm 0.12 (browser-side)
- **Cloud:** AWS — Lambda, S3, ECR (us-east-1)
- **Payments:** Stripe (one-time checkout, webhooks)
- **Deployment:** DigitalOcean App Platform, Docker, Nginx

## Project Structure

```
app/Http/Controllers/
  LambdaVideoController.php  # Pro server processing — S3 presigned URLs + Lambda invoke
  StripeController.php       # Checkout, webhooks, Pro email restore
  VideoController.php        # Legacy server-side FFmpeg (deprecated, replaced by Lambda)
app/Models/
  ProUser.php                # Pro user tracking (email, stripe IDs)
resources/js/Pages/
  Home.vue                   # Main SPA — all steps (upload, template, processing, done)
routes/web.php               # All routes (Inertia + API endpoints)
config/services.php          # Stripe + AWS config keys
lambda/
  Dockerfile                 # Lambda container image (Python 3.12 + static FFmpeg)
  handler.py                 # FFmpeg processing — downloads from S3, converts, uploads result
.do/app.yaml                 # DigitalOcean App Platform spec
Dockerfile                   # Production web build (PHP 8.2 + Apache + Node)
docker-entrypoint.sh         # Parses DATABASE_URL, runs migrations, caches config
```

## Routes

```
GET  /                          → Home (Inertia)
POST /api/checkout              → Stripe checkout session
POST /api/stripe/webhook        → Stripe webhook handler
POST /api/restore               → Email-based Pro status check
POST /api/process/init          → Get presigned S3 upload URL + jobId (Pro only)
POST /api/process/start         → Trigger Lambda processing (Pro only)
GET  /api/process/status/{jobId} → Poll processing progress + get download URL
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
AWS_ACCESS_KEY_ID=...
AWS_SECRET_ACCESS_KEY=...
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=convertportrait-processing
AWS_LAMBDA_FUNCTION=convertportrait-processor
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
- `AWS_ACCESS_KEY_ID`, `AWS_SECRET_ACCESS_KEY` — IAM user `convertportrait-laravel` (SECRET)
- `AWS_DEFAULT_REGION=us-east-1`, `AWS_BUCKET=convertportrait-processing`, `AWS_LAMBDA_FUNCTION=convertportrait-processor`
- `APP_ENV=production`, `APP_DEBUG=false`

### Infrastructure

- **Instance:** `apps-s-1vcpu-2gb` (1 vCPU, 2GB RAM)
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
  - Server (Pro): Video uploaded directly to S3 via presigned URL → Lambda runs FFmpeg (4GB RAM, 6 vCPU) → result downloaded from S3. No video bytes flow through the web server.
- **CORS headers required:** FFmpeg.wasm needs `Cross-Origin-Opener-Policy: same-origin` and `Cross-Origin-Embedder-Policy: credentialless`. Set in Apache config and Vite dev server.
- **Stripe webhook flow:** `checkout.session.completed` → save email to `pro_users` → user enters email on redirect → `/api/restore` confirms Pro status → saved to localStorage.

## Common Tasks

### Add a new background template

1. Add template object to `templates` array in `Home.vue` (id, name, description, pro flag)
2. Add canvas preview drawing in `drawPreview()` function
3. Add FFmpeg filter string in `buildFilter()` (browser-side, in Home.vue)
4. Add FFmpeg filter in `lambda/handler.py` → `build_filter()` (server-side)
5. Add template name to validation in `LambdaVideoController::init()` (`in:blurred,gradient,solid,pattern`)

### Change pricing

- `StripeController::createCheckout()` — `unit_amount` in cents (1999 = $19.99)
- Update copy in upgrade modal in `Home.vue`

### Database changes

```bash
php artisan make:migration <name>
php artisan migrate          # local
# Production: runs automatically on deploy via docker-entrypoint.sh
```

## AWS Lambda Architecture

```
Browser → S3 presigned PUT → Laravel triggers Lambda → Lambda runs FFmpeg → S3 output
Browser polls Laravel → Laravel reads S3 status JSON → returns real progress + download URL
```

### Server conversion flow

1. `POST /api/process/init` — Laravel validates Pro, creates S3 status JSON, returns `{jobId, uploadUrl}` (presigned S3 PUT)
2. Browser PUTs video directly to S3 (real upload progress via XHR)
3. `POST /api/process/start` — Laravel verifies upload exists in S3, invokes Lambda async
4. Lambda downloads from S3, runs FFmpeg with `-progress pipe:1`, writes status JSON to S3 every ~5%
5. `GET /api/process/status/{jobId}` — Laravel reads S3 status JSON. When done, returns presigned download URL
6. Browser downloads result directly from S3

### AWS resources (us-east-1)

- **S3 bucket:** `convertportrait-processing` — lifecycle deletes all objects after 1 day
- **ECR repo:** `convertportrait-processor` — Lambda container image
- **Lambda function:** `convertportrait-processor` — 4096MB RAM, 900s timeout, 10GB ephemeral
- **IAM user:** `convertportrait-laravel` — S3 PutObject/GetObject + Lambda InvokeFunction
- **IAM role:** `convertportrait-lambda-role` — S3 GetObject/PutObject + CloudWatch Logs

### Updating the Lambda function

```bash
cd lambda
aws ecr get-login-password --region us-east-1 | docker login --username AWS --password-stdin 432029609430.dkr.ecr.us-east-1.amazonaws.com
docker build --platform linux/amd64 -t convertportrait-processor .
docker tag convertportrait-processor:latest 432029609430.dkr.ecr.us-east-1.amazonaws.com/convertportrait-processor:latest
docker push 432029609430.dkr.ecr.us-east-1.amazonaws.com/convertportrait-processor:latest
aws lambda update-function-code --function-name convertportrait-processor --image-uri 432029609430.dkr.ecr.us-east-1.amazonaws.com/convertportrait-processor:latest --region us-east-1
```

### Lambda logs

```bash
aws logs tail /aws/lambda/convertportrait-processor --region us-east-1 --since 30m --follow
```

## Gotchas

- The CSRF middleware exempts `api/stripe/webhook` and `api/process/*` (see `bootstrap/app.php`)
- Lambda timeout is 900s (15 min). S3 objects auto-delete after 24h via lifecycle rules
- FFmpeg.wasm loads from unpkg CDN (`https://unpkg.com/@ffmpeg/core@0.12.6/dist/esm`)
- Pro status check on server uses `X-Pro-Email` header — no session/token auth
- DO managed DB requires SSL (`DB_SSLMODE=require`, set in entrypoint)
- S3 CORS must allow both PUT (upload) and GET (download) from convertportrait.com
- Blade template: `@type` and `@context` in JSON-LD must be escaped as `@@type`/`@@context`
- Lambda Docker image must be built with `--platform linux/amd64` (even on ARM Macs)
