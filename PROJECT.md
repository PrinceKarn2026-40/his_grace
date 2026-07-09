# Hisgrace — E-Commerce Fashion Store

A full-featured fashion e-commerce web application built with **Laravel 12**, **Jetstream**, **Livewire**, and **Bootstrap 5**. Designed for a Rwandan fashion brand with a modern dark/red UI theme, Google OAuth, MoMo payment support, and separate admin/customer dashboards.

---

## Table of Contents

- [Tech Stack](#tech-stack)
- [Project Structure](#project-structure)
- [Features](#features)
- [Database Schema](#database-schema)
- [Models & Relationships](#models--relationships)
- [Controllers](#controllers)
- [Routes](#routes)
- [Views & Layouts](#views--layouts)
- [Authentication](#authentication)
- [Admin Panel](#admin-panel)
- [Customer Dashboard](#customer-dashboard)
- [Shop / Storefront](#shop--storefront)
- [UI Design System](#ui-design-system)
- [Environment Configuration](#environment-configuration)
- [Installation & Setup](#installation--setup)
- [Key Artisan Commands](#key-artisan-commands)

---

## Tech Stack

| Layer        | Technology                                      |
|--------------|-------------------------------------------------|
| Framework    | Laravel 12 (PHP 8.2+)                           |
| Auth         | Laravel Jetstream 5 + Fortify + Sanctum         |
| OAuth        | Laravel Socialite 5 (Google)                    |
| Frontend     | Bootstrap 5.3.3, Bootstrap Icons, Chart.js      |
| Fonts        | Playfair Display (headings), Inter (body)       |
| Realtime UI  | Livewire 3                                      |
| Database     | MySQL (via XAMPP) / SQLite (dev fallback)       |
| File Storage | Laravel Storage — `public` disk                 |
| Payments     | MTN MoMo (manual confirmation flow)             |
| Notifications| Laravel Database Notifications                  |
| Testing      | PHPUnit 11                                      |
| Dev Tools    | Laravel Pail, Pint, Sail, Tinker                |

---

## Project Structure

```
Hisgrace/
├── app/
│   ├── Actions/Fortify/          # Jetstream user actions
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   └── AdminController.php
│   │   │   ├── CartController.php
│   │   │   ├── CheckoutController.php
│   │   │   ├── CustomerController.php
│   │   │   ├── OrderController.php
│   │   │   ├── ReviewController.php
│   │   │   ├── ShopController.php
│   │   │   ├── SocialAuthController.php
│   │   │   └── WishlistController.php
│   │   └── Middleware/
│   │       └── AdminMiddleware.php
│   ├── Models/
│   │   ├── Cart.php
│   │   ├── Category.php
│   │   ├── Order.php
│   │   ├── OrderItem.php
│   │   ├── Payment.php
│   │   ├── Product.php
│   │   ├── Review.php
│   │   ├── User.php
│   │   └── Wishlist.php
│   ├── Notifications/
│   │   └── OrderPlaced.php
│   └── Providers/
│       └── AppServiceProvider.php
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   └── views/
│       ├── admin/
│       ├── auth/
│       ├── customer/
│       ├── layouts/
│       └── shop/
├── routes/
│   └── web.php
├── public/
│   └── storage -> ../storage/app/public  (symlink)
└── .env
```

---

## Features

### Storefront
- Animated hero section with **live product slider** (auto-advances every 3.5s)
- Live ticker / announcement bar
- Category cards grid
- Featured Products & New Arrivals sections
- Promo banner strip
- Product detail page with reviews and rating
- Search functionality
- Wishlist (toggle, authenticated)
- Shopping cart (guest + authenticated)
- Order tracking by order number

### Checkout & Payments
- Multi-step checkout form (shipping details)
- **MTN MoMo** payment — manual confirmation flow with instructions page
- Payment status management (pending → confirmed → failed)

### Authentication
- Email/password registration & login (Jetstream/Fortify)
- **Google OAuth** (Laravel Socialite) — sign in / sign up with Google
- Email verification required
- Two-factor authentication (Jetstream)
- Passkeys support (Laravel Passkeys)
- Password reset flow

### Admin Panel
- Dashboard with Chart.js revenue & order status charts
- Product CRUD (create, edit, delete) with image upload & preview
- Category management
- Order management with status updates
- Customer management (view, toggle active/inactive)
- Payments management
- Reports page
- Settings page

### Customer Dashboard
- Welcome banner with stats (orders, wishlist, cart)
- Order history with detail view
- Profile management (name, email, photo)
- Order tracking
- Notifications center

### UI / UX
- Fully responsive — mobile, tablet, laptop
- Dark theme with red accent (`#c0392b` / `#e74c3c`)
- Glassmorphism login/register cards
- Floating WhatsApp button (FAB) with pulse animation
- Sidebar navigation for admin & customer (slide-in on mobile)
- Toast notifications

---

## Database Schema

### `users`
| Column | Type | Notes |
|---|---|---|
| id | bigint PK | |
| name | string | |
| email | string unique | |
| password | string nullable | null for OAuth users |
| is_admin | boolean | default false |
| google_id | string nullable unique | Google OAuth |
| email_verified_at | timestamp nullable | |
| profile_photo_path | string nullable | |
| two_factor_secret | text nullable | |
| two_factor_recovery_codes | text nullable | |

### `categories`
| Column | Type |
|---|---|
| id | bigint PK |
| name | string |
| slug | string unique |
| image | string nullable |

### `products`
| Column | Type | Notes |
|---|---|---|
| id | bigint PK | |
| category_id | FK → categories | |
| name | string | |
| slug | string unique | |
| description | text nullable | |
| price | decimal(10,2) | |
| sale_price | decimal(10,2) nullable | |
| stock | integer | default 0 |
| image | string nullable | stored in `storage/app/public/products/` |
| gender | enum | Men, Women, Unisex |
| featured | boolean | default false |
| is_new | boolean | default false |

### `orders`
| Column | Type |
|---|---|
| id | bigint PK |
| user_id | FK → users |
| total | decimal(10,2) |
| status | enum: pending, processing, shipped, delivered, cancelled |
| payment_method | string |
| payment_reference | string nullable |
| shipping_name | string |
| shipping_email | string |
| shipping_phone | string |
| shipping_address | string |
| shipping_city | string |
| shipping_country | string |

### `order_items`
| Column | Type |
|---|---|
| id | bigint PK |
| order_id | FK → orders |
| product_id | FK → products |
| quantity | integer |
| price | decimal(10,2) |

### `carts`
| Column | Type |
|---|---|
| id | bigint PK |
| user_id | FK → users nullable |
| session_id | string nullable |
| product_id | FK → products |
| quantity | integer |

### `wishlists`
| Column | Type |
|---|---|
| id | bigint PK |
| user_id | FK → users |
| product_id | FK → products |

### `reviews`
| Column | Type |
|---|---|
| id | bigint PK |
| user_id | FK → users |
| product_id | FK → products |
| rating | tinyint (1–5) |
| comment | text nullable |

### `payments`
| Column | Type |
|---|---|
| id | bigint PK |
| order_id | FK → orders |
| amount | decimal(10,2) |
| method | string |
| reference | string nullable |
| status | enum: pending, confirmed, failed |

---

## Models & Relationships

### `User`
- `hasMany` → Orders, Reviews, Wishlists, Cart items
- Traits: `HasApiTokens`, `HasProfilePhoto`, `TwoFactorAuthenticatable`, `Notifiable`
- `$fillable`: name, email, password, is_admin, profile_photo_path, google_id, email_verified_at

### `Product`
- `belongsTo` → Category
- `hasMany` → Reviews, OrderItems
- Computed attributes: `effective_price`, `average_rating`

### `Order`
- `belongsTo` → User
- `hasMany` → OrderItems, Payments

### `Category`
- `hasMany` → Products

### `Cart`
- `belongsTo` → User (nullable — supports guest carts via session)
- `belongsTo` → Product

### `Wishlist`
- `belongsTo` → User, Product

### `Review`
- `belongsTo` → User, Product

### `Payment`
- `belongsTo` → Order

---

## Controllers

| Controller | Responsibility |
|---|---|
| `ShopController` | Home page, product listing, product detail |
| `CartController` | View cart, add, update quantity, remove item |
| `CheckoutController` | Checkout form, store order, MoMo instructions & confirm |
| `OrderController` | Customer order list & detail |
| `CustomerController` | Customer dashboard, profile, order tracking |
| `ReviewController` | Submit product reviews |
| `WishlistController` | Toggle wishlist items, view wishlist |
| `SocialAuthController` | Google OAuth redirect & callback |
| `Admin/AdminController` | All admin panel pages & CRUD operations |

### `AdminController` Actions
- `dashboard()` — stats, charts data, recent orders, top products
- `products()` / `createProduct()` / `storeProduct()` / `editProduct()` / `updateProduct()` / `destroyProduct()`
- `orders()` / `showOrder()` / `updateOrderStatus()`
- `categories()` / `storeCategory()` / `updateCategory()` / `destroyCategory()`
- `customers()` / `showCustomer()` / `toggleCustomerStatus()`
- `payments()` / `updatePaymentStatus()`
- `reports()` / `settings()` / `updateSettings()`

---

## Routes

### Public
| Method | URI | Name | Controller |
|---|---|---|---|
| GET | `/` | `home` | ShopController@home |
| GET | `/shop` | `shop` | ShopController@index |
| GET | `/shop/{product:slug}` | `shop.show` | ShopController@show |
| GET | `/cart` | `cart` | CartController@index |
| PATCH | `/cart/{cart}` | `cart.update` | CartController@update |
| DELETE | `/cart/{cart}` | `cart.remove` | CartController@remove |
| GET | `/auth/google` | `auth.google` | SocialAuthController@redirectToGoogle |
| GET | `/auth/google/callback` | `auth.google.callback` | SocialAuthController@handleGoogleCallback |

### Authenticated (`auth:sanctum` + `verified`)
| Method | URI | Name |
|---|---|---|
| GET | `/dashboard` | `dashboard` (redirects by role) |
| POST | `/cart/{product}` | `cart.add` |
| GET | `/account` | `customer.dashboard` |
| GET | `/account/profile` | `customer.profile` |
| POST | `/account/profile` | `customer.profile.update` |
| GET/POST | `/account/track` | `customer.track` |
| GET | `/checkout` | `checkout` |
| POST | `/checkout` | `checkout.store` |
| GET | `/checkout/momo/{order}` | `checkout.momo.instructions` |
| POST | `/checkout/momo/{order}/confirm` | `checkout.momo.confirm` |
| GET | `/orders` | `orders.index` |
| GET | `/orders/{order}` | `orders.show` |
| POST | `/products/{product}/reviews` | `reviews.store` |
| GET | `/wishlist` | `wishlist` |
| POST | `/wishlist/{product}` | `wishlist.toggle` |
| GET | `/notifications` | `notifications` |

### Admin (`auth:sanctum` + `admin` middleware, prefix `/admin`)
| Method | URI | Name |
|---|---|---|
| GET | `/admin/` | `admin.dashboard` |
| GET/POST | `/admin/products` | `admin.products` / `admin.products.store` |
| GET | `/admin/products/create` | `admin.products.create` |
| GET/PUT/DELETE | `/admin/products/{product}` | edit / update / destroy |
| GET/POST | `/admin/categories` | list / store |
| PUT/DELETE | `/admin/categories/{category}` | update / destroy |
| GET | `/admin/orders` | `admin.orders` |
| GET | `/admin/orders/{order}` | `admin.orders.show` |
| PATCH | `/admin/orders/{order}/status` | `admin.orders.status` |
| GET | `/admin/customers` | `admin.customers` |
| GET | `/admin/customers/{user}` | `admin.customers.show` |
| PATCH | `/admin/customers/{user}/toggle` | `admin.customers.toggle` |
| GET | `/admin/payments` | `admin.payments` |
| PATCH | `/admin/payments/{payment}/status` | `admin.payments.status` |
| GET | `/admin/reports` | `admin.reports` |
| GET/POST | `/admin/settings` | `admin.settings` / `admin.settings.update` |

---

## Views & Layouts

### Layouts
| File | Used By |
|---|---|
| `layouts/shop.blade.php` | All public storefront pages |
| `layouts/admin.blade.php` | All admin panel pages |
| `layouts/customer.blade.php` | All customer dashboard pages |
| `layouts/guest.blade.php` | Auth pages (login, register, etc.) |

### Shop Views (`resources/views/shop/`)
- `home.blade.php` — Landing page with hero slider, categories, featured/new arrivals
- `index.blade.php` — Full product listing with filters
- `show.blade.php` — Product detail with reviews
- `cart.blade.php` — Shopping cart
- `checkout.blade.php` — Checkout form
- `orders.blade.php` / `order-detail.blade.php` — Order history
- `wishlist.blade.php` — Saved products
- `notifications.blade.php` — User notifications
- `partials/product-card.blade.php` — Reusable product card component

### Admin Views (`resources/views/admin/`)
- `dashboard.blade.php` — Stats, Chart.js charts, recent orders, top products
- `products/create.blade.php` / `products/edit.blade.php` — Product form with image preview
- `categories.blade.php` — Category CRUD
- `orders.blade.php` / `order-detail.blade.php` — Order management
- `customers.blade.php` / `customer-detail.blade.php` — Customer management
- `payments.blade.php` — Payment records
- `reports.blade.php` — Sales reports
- `settings.blade.php` — App settings

### Customer Views (`resources/views/customer/`)
- `dashboard.blade.php` — Welcome banner, stats, recent orders
- `orders.blade.php` / `order-detail.blade.php` — Order history
- `profile.blade.php` — Profile edit
- `cart.blade.php` — Cart view
- `checkout.blade.php` — Checkout
- `track-order.blade.php` — Order tracking
- `momo-instructions.blade.php` — MoMo payment instructions

### Auth Views (`resources/views/auth/`)
- `login.blade.php` — Dark glassmorphism card, Google sign-in button
- `register.blade.php` — Same theme, perks row, Google sign-up button
- `forgot-password.blade.php`, `reset-password.blade.php`, `verify-email.blade.php`

---

## Authentication

### Standard (Jetstream + Fortify)
- Registration with email verification
- Login with remember me
- Password reset via email
- Two-factor authentication (TOTP)
- Passkeys (WebAuthn)
- API tokens (Sanctum)

### Google OAuth (Socialite)
```
GET /auth/google          → redirectToGoogle()
GET /auth/google/callback → handleGoogleCallback()
```
- Uses `updateOrCreate` on `google_id` field
- Password is set to `null` for OAuth-only users
- Auto-marks email as verified

### Admin Guard
`AdminMiddleware` checks `auth()->user()->is_admin === true`, aborts with 403 otherwise.

---

## Admin Panel

Access: `/admin/` — requires `is_admin = true` on the user record.

**Dashboard widgets:**
- Total revenue, total orders, total customers, total products
- Revenue over time (Chart.js line chart)
- Order status breakdown (Chart.js doughnut chart)
- Recent orders table
- Top selling products list

**Product Management:**
- Two-column form layout
- Live image preview on file select
- Toggle cards for "Featured Product" and "New Arrival"
- Gender selector (Men / Women / Unisex)
- Category dropdown
- Sale price field (optional)
- Red gradient "Save Product" / "Save Changes" button

---

## Customer Dashboard

Access: `/account` — requires authentication.

**Sections:**
- Dark welcome banner with user name and stats (order count, wishlist count, cart count)
- Recent orders table with status badges
- Quick action buttons (Browse Shop, Track Order, View Wishlist)
- Profile edit form

---

## Shop / Storefront

### Home Page (`ShopController@home`)
Data passed to view:
- `$heroSlider` — latest 6 products (for hero slider)
- `$featured` — products where `featured = true`
- `$newArrivals` — products where `is_new = true`
- `$categories` — all categories

### Hero Slider
- Auto-advances every 3.5 seconds
- Shows product image, name, category, price
- Manual prev/next controls
- Dot indicators

### WhatsApp FAB
- Fixed bottom-right floating button
- Pulse animation ring
- Tooltip on hover
- Pre-filled message: `"Hello! I'm interested in your products."`
- Links to `https://wa.me/250795919537`

---

## UI Design System

### Colors
```css
--red:       #c0392b   /* primary accent */
--red-dark:  #96281b   /* hover states */
--red-light: #e74c3c   /* highlights */
--dark:      #0f0f0f   /* background */
--dark-2:    #1a1a1a   /* cards */
--dark-3:    #242424   /* inputs */
```

### Typography
- **Headings / Brand**: Playfair Display (Google Fonts)
- **Body / UI**: Inter (Google Fonts)

### Breakpoints (Bootstrap 5)
| Breakpoint | Width |
|---|---|
| xs (mobile) | < 576px |
| sm | ≥ 576px |
| md | ≥ 768px |
| lg | ≥ 992px |
| xl | ≥ 1200px |

### Sidebar Behavior
- Hidden at `max-width: 768px` via `transform: translateX(-100%)`
- Toggled by hamburger button (`.show` class)
- Overlay backdrop on mobile
- Close on outside click

### Product Cards
- Bootstrap grid: `col-6 col-md-4 col-lg-3`
- Red "Add to Cart" button
- Sale price badge overlay
- Wishlist heart icon
- Image via `Storage::url($product->image)`

---

## Environment Configuration

Key `.env` values:

```env
APP_NAME=Hisgrace
APP_URL=http://localhost/dashboard/Hisgrace/public
ASSET_URL=http://localhost/dashboard/Hisgrace/public

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hisgrace
DB_USERNAME=root
DB_PASSWORD=

FILESYSTEM_DISK=public

GOOGLE_CLIENT_ID=<your-google-client-id>
GOOGLE_CLIENT_SECRET=<your-google-client-secret>
GOOGLE_REDIRECT_URI=http://localhost/dashboard/Hisgrace/public/auth/google/callback

MAIL_MAILER=smtp
```

> **Important**: `FILESYSTEM_DISK=public` is required so `Storage::url()` generates correct full URLs for product images.

### `AppServiceProvider` Storage Fix
```php
// app/Providers/AppServiceProvider.php
config(['filesystems.disks.public.url' => env('APP_URL') . '/storage']);
```

---

## Installation & Setup

### Prerequisites
- PHP 8.2+
- Composer
- Node.js & npm
- XAMPP (MySQL + Apache) or any LAMP/LEMP stack

### Steps

```bash
# 1. Clone / place project in XAMPP htdocs
cd C:/xampp/htdocs/dashboard/Hisgrace

# 2. Install PHP dependencies
composer install

# 3. Copy environment file
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Configure .env (DB credentials, APP_URL, Google OAuth)

# 6. Run migrations
php artisan migrate

# 7. Create storage symlink
php artisan storage:link

# 8. Install JS dependencies & build assets
npm install
npm run build

# 9. (Optional) Seed database
php artisan db:seed
```

### Create Admin User
```bash
php artisan tinker
>>> \App\Models\User::where('email', 'your@email.com')->update(['is_admin' => true]);
```

### Access URLs
| Page | URL |
|---|---|
| Storefront | `http://localhost/dashboard/Hisgrace/public/` |
| Admin Panel | `http://localhost/dashboard/Hisgrace/public/admin` |
| Customer Dashboard | `http://localhost/dashboard/Hisgrace/public/account` |
| Login | `http://localhost/dashboard/Hisgrace/public/login` |
| Register | `http://localhost/dashboard/Hisgrace/public/register` |

---

## Key Artisan Commands

```bash
# Run migrations
php artisan migrate

# Rollback last migration
php artisan migrate:rollback

# Create storage symlink
php artisan storage:link

# Clear all caches
php artisan optimize:clear

# Run tests
php artisan test

# Start dev server (with queue, logs, vite)
composer run dev

# Lint / format code
./vendor/bin/pint
```

---

## Migrations (Chronological)

| File | Description |
|---|---|
| `0001_01_01_000000` | Create users table |
| `0001_01_01_000001` | Create cache table |
| `0001_01_01_000002` | Create jobs table |
| `2026_07_06_161126` | Add two-factor columns to users |
| `2026_07_06_161127` | Create passkeys table |
| `2026_07_06_161215` | Create personal access tokens table |
| `2026_07_06_200000` | Create categories table |
| `2026_07_06_200001` | Create products table |
| `2026_07_06_200002` | Create shop tables (orders, order_items, carts) |
| `2026_07_06_200003` | Create reviews, wishlists, notifications |
| `2026_07_06_200004` | Add `is_admin` to users |
| `2026_07_08_174120` | Create payments table |
| `2026_07_08_194425` | Add `google_id` to users, make `password` nullable |

---

## WhatsApp Contact

Business WhatsApp: **+250 795 919 537**
Link format: `https://wa.me/250795919537`

---

*Built with ❤️ using Laravel 12 — Hisgrace Fashion Store*
