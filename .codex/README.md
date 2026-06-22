# Project Context

This project is a Laravel 13 + Inertia Vue 3 starter with Breeze auth, Socialite, Wayfinder, and Tailwind.

## Where Things Live

- Auth routes: `routes/auth.php`
- Auth controllers: `app/Http/Controllers/Auth`
- Auth pages: `resources/js/Pages/Auth`
- Shared UI components: `resources/js/Components`
- User model: `app/Models/User.php`
- Service config: `config/services.php`

## Current OAuth Setup

- Google OAuth credentials come from `.env`:
  - `GOOGLE_CLIENT_ID`
  - `GOOGLE_CLIENT_SECRET`
  - `GOOGLE_REDIRECT_URI`
- Google auth uses Socialite routes:
  - `auth/google`
  - `auth/google/callback`
- Users are matched by `google_id` first, then by email.
- New Google logins create an account automatically.
- Existing users get linked to their Google account.

## Working Rules

- Prefer named routes and existing starter-kit components.
- Do not hardcode secrets or provider credentials.
- For PHP changes, run `vendor/bin/pint --dirty --format agent`.
- For auth and feature work, prefer Pest tests in `tests/Feature`.
- Follow the repo instructions in `AGENTS.md` and the relevant skill files under `.agents/skills`.

## Useful Commands

- `php artisan route:list`
- `php artisan test --compact`
- `vendor/bin/pint --dirty --format agent`
