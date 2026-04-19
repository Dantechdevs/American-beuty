# 🌸 American Beauty — Laravel 11 E-Commerce Platform

Premium skincare & beauty e-commerce built on Laravel 11 with M-PESA integration.

---

## 🚀 Quick Setup

### 1. Install Dependencies
```bash
composer install
npm install
```

### 2. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Configure .env
Edit `.env` and set:
```env
DB_DATABASE=american_beauty
DB_USERNAME=root
DB_PASSWORD=your_password

# M-PESA (get from Safaricom Daraja portal)
MPESA_CONSUMER_KEY=your_consumer_key
MPESA_CONSUMER_SECRET=your_consumer_secret
MPESA_SHORTCODE=174379
MPESA_PASSKEY=your_lipa_na_mpesa_passkey
MPESA_CALLBACK_URL=https://yourdomain.com/mpesa/callback
MPESA_ENV=sandbox
```

### 4. Database Setup
```bash
php artisan migrate --seed
```

### 5. Storage Link
```bash
php artisan storage:link
```

### 6. Build Assets
```bash
npm run build
# or for development:
npm run dev
```

### 7. Start Server
```bash
php artisan serve
```

Visit: http://localhost:8000

---

## 🔐 Admin Login
- **URL:** http://localhost:8000/admin
- **Email:** admin@americanbeauty.com
- **Password:** password

---

## 📱 M-PESA Setup Guide

### Step 1 — Safaricom Daraja Portal
1. Go to https://developer.safaricom.co.ke
2. Create an account and log in
3. Go to **My Apps** → Create a new app
4. Enable **Lipa na M-PESA Online** (STK Push)
5. Copy your **Consumer Key** and **Consumer Secret**

### Step 2 — Get Your Passkey
- For **sandbox**: Use the test passkey from the Daraja portal
- For **live**: Get your passkey from your Safaricom business account

### Step 3 — Set Callback URL
Your callback URL must be:
- Publicly accessible (not localhost)
- HTTPS secured
- Set in `.env` as `MPESA_CALLBACK_URL=https://yourdomain.com/mpesa/callback`

For local testing, use [ngrok](https://ngrok.com):
```bash
ngrok http 8000
# Use the HTTPS ngrok URL as your callback
```

### Step 4 — Go Live
Change in `.env`:
```env
MPESA_ENV=live
MPESA_SHORTCODE=your_actual_shortcode
```

---

## 🏗️ Project Structure

```
americanbeauty/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Admin panel controllers
│   │   │   ├── Auth/           # Login, register
│   │   │   └── Frontend/       # Shop, cart, checkout
│   │   └── Middleware/
│   │       └── AdminMiddleware.php
│   ├── Models/                 # All Eloquent models
│   └── Services/
│       ├── CartService.php     # Cart management
│       ├── OrderService.php    # Order creation
│       └── Payment/
│           └── MpesaService.php # Full M-PESA integration
├── database/
│   ├── migrations/             # Complete schema
│   └── seeders/               # Sample data + admin user
├── resources/views/
│   ├── layouts/               # app.blade.php, admin.blade.php
│   ├── frontend/              # Shop pages
│   ├── admin/                 # Admin panel
│   └── auth/                  # Login, register
├── routes/
│   └── web.php                # All routes
└── config/
    └── mpesa.php              # M-PESA config
```

---

## 💳 Payment Methods

| Method | Status | Notes |
|--------|--------|-------|
| M-PESA | ✅ Active | STK Push + Callback |
| Cash on Delivery | ✅ Active | Default |
| Stripe | ⚙️ Config needed | Add keys in .env |

---

## 📦 Default Categories
- Skincare (Moisturizers, Serums, Cleansers, Sunscreen)
- Makeup (Foundation, Lipstick, Eyeshadow)
- Haircare
- Fragrance
- Body Care
- Tools

---

## 🛠 Tech Stack
- **Backend:** Laravel 11, PHP 8.2+
- **Database:** MySQL
- **Frontend:** Blade, Tailwind CSS, Alpine.js
- **Payments:** M-PESA (Safaricom Daraja API), Stripe
- **Auth:** Laravel built-in

---

## 📞 Support
Built by Dantechdevs developers (https://ngwasidaniel.vercel.app/#contact) — American Beauty Project
