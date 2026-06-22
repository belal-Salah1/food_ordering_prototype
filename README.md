# MealBox - Food Ordering System

MealBox is a modern, full-stack food ordering application built with the Laravel 13, Inertia.js v2, and Vue 3. It features a seamless guest-to-customer flow, real-time order tracking, and a comprehensive admin management system.

## 🚀 Overview

The application provides a "restaurant-first" experience where users can browse a menu, manage a persistent guest cart, and transition seamlessly to a secure checkout. It supports dual languages (English and Arabic) and offers multiple payment methods including Cash on Delivery and Stripe.

## ✨ Features

### Customer Features
- **🌍 Localization**: Full support for English and Arabic with RTL layout switching.
- **🛒 Guest Cart**: Browse and add items to your cart without logging in. The cart is persisted locally.
- **🔐 Flexible Auth**: Traditional email/password login integrated with **Google OAuth** via Laravel Socialite.
- **💳 Smart Checkout**:
  - Remembers your intended destination after login.
  - Supports **Cash on Delivery** and **Stripe Integration**.
  - Interactive delivery address management.
- **📍 Order Tracking**: Visual real-time tracking (Preparing → Out for Delivery → Delivered).

### Admin Features
- **📊 Admin Dashboard**: High-level overview of system operations.
- **🍔 Product Management**: Full CRUD for menu items including image uploads and categorization.
- **📋 Order Management**: View all customer orders and update their fulfillment status.
- **🛡️ RBAC**: Role-Based Access Control using Spatie Permissions.

## 🛠️ Tech Stack
- **Backend**: PHP 8.3, Laravel 13, Laravel Socialite, Spatie Laravel-Permission.
- **Frontend**: Vue 3, Inertia.js v2, Tailwind CSS v3.
- **Database**: MySQL / SQLite.
- **Testing**: Pest PHP.
- **Tools**: Laravel Pint (Formatting), Vite (Bundling).

## 🔀 User Flow
1. **Browse**: User explores the menu (Guest or Authenticated).
2. **Cart**: User adds items to the local guest cart.
3. **Checkout**: User clicks checkout. If not logged in, they are redirected to login/register and then sent back to checkout.
4. **Order**: User provides address, selects payment (COD/Stripe), and confirms.
5. **Success**: Order is created, cart is cleared.
6. **Track**: User is redirected to a live order tracking page.

## ⚙️ Installation & Setup

### Prerequisites
- PHP 8.3+
- Node.js & NPM
- Composer

### Steps
1. **Clone the repository**:
   ```bash
   git clone <repository-url>
   cd food_ordering_task
   ```

2. **Install dependencies**:
   ```bash
   composer install
   npm install
   ```

3. **Environment Setup**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Note: Ensure you configure your `DB_*` and `STRIPE_*` variables in `.env`.*

4. **Database & Seeding**:
   Run the migrations and seed the initial data (including the Admin user).
   ```bash
   php artisan migrate --seed
   ```

5. **Run the Application**:
   ```bash
   # In one terminal
   php artisan serve
   
   # In another terminal
   npm run dev
   ```

## 🔑 Admin Access
The project comes with a pre-seeded admin account for testing:
- **URL**: `/admin`
- **Email**: `admin@mealbox.com`
- **Password**: `password`

## 🧪 Testing
Run the test suite using Pest:
```bash
php artisan test
```

---
Built with ❤️ using the Laravel ecosystem.