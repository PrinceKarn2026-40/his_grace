# HisGrace Fashion Store

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12-red?style=for-the-badge&logo=laravel" alt="Laravel 12">
  <img src="https://img.shields.io/badge/PHP-8.2-blue?style=for-the-badge&logo=php" alt="PHP 8.2">
  <img src="https://img.shields.io/badge/MySQL-8.0-orange?style=for-the-badge&logo=mysql" alt="MySQL">
  <img src="https://img.shields.io/badge/Docker-Ready-2496ED?style=for-the-badge&logo=docker" alt="Docker">
  <img src="https://img.shields.io/badge/Deployed-Railway-8B5CF6?style=for-the-badge" alt="Railway">
</p>

A full-featured e-commerce fashion web application built for a Rwandan fashion brand. Built with **Laravel 12**, **Jetstream**, **Livewire**, and **Bootstrap 5** featuring a modern dark/red UI theme, Google OAuth, MTN MoMo payment support, and separate admin/customer dashboards.

---

## Live Demo

**Production URL:** https://hisgrace-production.up.railway.app

**Admin Panel:** https://hisgrace-production.up.railway.app/admin

---

## Tech Stack

| Layer | Technology |
|---|---|
| Framework | Laravel 12 (PHP 8.2+) |
| Auth | Laravel Jetstream 5 + Fortify + Sanctum |
| OAuth | Laravel Socialite 5 (Google) |
| Frontend | Bootstrap 5.3.3, Bootstrap Icons, Chart.js |
| Fonts | Playfair Display (headings), Inter (body) |
| Realtime UI | Livewire 3 |
| Database | MySQL 8.0 |
| File Storage | Laravel Storage — `public` disk |
| Payments | MTN MoMo (manual confirmation flow) |
| Notifications | Laravel Database Notifications |
| Testing | PHPUnit 11 |
| Containerization | Docker + Docker Compose |
| CI/CD | GitHub Actions |
| Deployment | Railway |

---

## Features

### Storefront
- Animated hero section with live product slider
- Category cards grid
- Featured Products & New Arrivals sections
- Product detail page with reviews and ratings
- Search and filtering (category, gender, price, sort)
- Wishlist (toggle, authenticated users)
- Shopping cart (guest + authenticated)
- Order tracking by order number
- Coming Soon products with countdown timers
- WhatsApp floating action button

### Checkout & Payments
- Multi-step checkout form with shipping details
- MTN MoMo payment — manual confirmation flow
- Payment status management (pending → confirmed → failed)
- Order confirmation with email notification

### Authentication
- Email/password registration & login (Jetstream/Fortify)
- Google OAuth (Laravel Socialite)
- Email verification
- Two-factor authentication (TOTP)
- Passkeys support (WebAuthn)
- Password reset via email

### Admin Panel
- Dashboard with Chart.js revenue & order status charts
- Product CRUD with image upload & URL support
- Category management
- Order management with status updates
- Customer management — full CRUD (create, view, edit, delete, suspend)
- Payments management
- Reports page
- Settings page

### Customer Dashboard
- Welcome banner with stats (orders, wishlist, cart)
- Order history with detail view
- Profile management (name, email, photo)
- Order tracking
- Notifications center

---

## Database Schema

| Table | Description |
|---|---|
| `users` | Customers and admins |
| `categories` | Product categories |
| `products` | Product listings |
| `orders` | Customer orders |
| `order_items` | Items within each order |
| `carts` | Shopping cart (guest + auth) |
| `wishlists` | Saved products per user |
| `reviews` | Product reviews and ratings |
| `payments` | Payment records |
| `sessions` | Database sessions |
| `notifications` | Laravel notifications |

---

## Local Installation

### Prerequisites
- PHP 8.2+
- Composer
- Node.js & npm
- MySQL (XAMPP or any LAMP/LEMP stack)

### Steps

```bash
# 1. Clone the repository
git clone https://github.com/PrinceKarn2026-40/his_grace.git
cd his_grace

# 2. Install PHP dependencies
composer install

# 3. Copy environment file
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Configure .env (DB credentials, APP_URL, Google OAuth)

# 6. Run migrations
php artisan migrate

# 7. Seed the database
php artisan db:seed

# 8. Create storage symlink
php artisan storage:link

# 9. Install JS dependencies & build assets
npm install && npm run build
```

### Access URLs

| Page | URL |
|---|---|
| Storefront | `http://localhost/dashboard/Hisgrace/public/` |
| Admin Panel | `http://localhost/dashboard/Hisgrace/public/admin` |
| Customer Dashboard | `http://localhost/dashboard/Hisgrace/public/account` |

### Default Credentials (after seeding)

| Role | Email | Password |
|---|---|---|
| Admin | libprince1999@gmail.com | 0795919537 |
| Customer | user@hisgrace.com | password |

---

## Docker

### Run with Docker Compose

```bash
# Build and start all services (app + nginx + mysql)
docker-compose up --build

# Visit http://localhost:8080
```

### Run with Dockerfile only

```bash
docker build -t hisgrace:latest .
docker run -p 80:80 --env-file .env hisgrace:latest
```

---

## CI/CD Pipeline

GitHub Actions workflow at `.github/workflows/ci.yml`:

1. **Build & Test** — installs dependencies, runs migrations, executes PHPUnit tests
2. **Docker Build** — builds the Docker image and runs a container smoke test

Triggers on every push to `main` branch.

---

## Deployment (Railway)

1. Connect GitHub repo to Railway
2. Add MySQL plugin
3. Set environment variables (see `.env.example`)
4. Railway auto-deploys on every push to `main`

Key environment variables:

```env
APP_ENV=production
APP_KEY=<generated>
APP_URL=https://hisgrace-production.up.railway.app
DB_CONNECTION=mysql
DB_HOST=mysql.railway.internal
FILESYSTEM_DISK=public
```

---

## Project Structure

```
his_grace/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/AdminController.php
│   │   ├── CartController.php
│   │   ├── CheckoutController.php
│   │   ├── CustomerController.php
│   │   ├── OrderController.php
│   │   ├── ReviewController.php
│   │   ├── ShopController.php
│   │   ├── SocialAuthController.php
│   │   └── WishlistController.php
│   ├── Models/
│   └── Notifications/
├── database/migrations/
├── resources/views/
│   ├── admin/
│   ├── auth/
│   ├── customer/
│   ├── layouts/
│   └── shop/
├── routes/web.php
├── Dockerfile
├── docker-compose.yml
├── railway.json
└── .github/workflows/ci.yml
```

---

## Security

- Password hashing (bcrypt, 12 rounds)
- CSRF protection on all forms
- Admin middleware protecting all `/admin` routes
- Input validation on all forms
- SQL injection prevention via Eloquent ORM
- HTTPS enforced in production
- Trusted proxy configuration for Railway

---

## Author

**Prince Karn**
Faculty of Computing and Information Sciences — UNILAK
Course: EWA408510 – E-Commerce and Web Application
Academic Year: 2025–2026

---

*Built with ❤️ using Laravel 12 — HisGrace Fashion Store, Kigali Rwanda*
