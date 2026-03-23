# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Running the App

This project runs on XAMPP (Apache + MySQL). Start XAMPP and access the app at:
- `http://localhost/AWS` — login page (index.php)

For live reload during development:
```
browser-sync start --proxy "http://localhost:8080/AWS" --files "**/*.php, **/*.css, **/*.js"
```

Install PHP dependencies:
```
composer install
```

## Environment Setup

Copy `.env.example` to `.env` and fill in your MySQL credentials. Required vars: `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`, `DB_PORT`.

The `.env` file must live in the project root — `Database/db_connect.php` loads it via `vlucas/phpdotenv`.

## Architecture

### Request Flow

Every page follows the same pattern:
1. A root-level `.php` file (e.g., `customer.php`) is the URL entry point.
2. It `require_once`s the corresponding logic file from a subdirectory (e.g., `Customer/customer_logic.php`).
3. The logic file starts the session, checks auth, connects to DB, handles POST, then falls through to inline HTML output.

There is no MVC framework — logic and HTML are in the same file. The root `.php` files exist solely to give XAMPP-friendly URLs.

### Auth & Session

- `Login/login_logic.php` sets `$_SESSION["loggedin"] = true` and `$_SESSION["id"]` (user_id) and `$_SESSION["username"]`.
- Every protected page checks these session vars at the top; unauthenticated requests redirect to `login.php`.
- Flash messages use `$_SESSION['flash_message']` / `$_SESSION['flash_type']` with a POST-redirect-GET pattern.

### Multi-user Data Isolation

All queries for user-owned data (customers, products, vendors, orders) filter by `user_id = :user_id` using `$_SESSION['id']`. Never query these tables without this filter.

### Database

Single PDO connection created in `Database/db_connect.php`, available as `$pdo` after `require_once`. Uses `ERRMODE_EXCEPTION` and real prepared statements (`ATTR_EMULATE_PREPARES => false`).

Schema is in `sqlQuery.txt`. Key tables:
- `User_List` — accounts
- `customers`, `products`, `vendors` — per-user master data
- `orders` + `order_items` — order header and line items (order_items.subtotal is a GENERATED column)
- `order_tenures` — installment schedule per order

### Order Creation (Order/order_logic.php)

Order creation is a single PDO transaction inserting into `orders`, `order_items`, and `order_tenures` atomically via `saveOrder()`. Tenure due dates are computed by multiplying `tenure_interval` × `period_unit` (days: daily=1, weekly=7, monthly=30, yearly=365) and stepping forward from the delivery date.

### Search (AJAX)

`Search/search_logic.php` is a JSON API endpoint consumed by `Search/search_handler.js`. It accepts:
- `?category=customers|suppliers|products|orders|installments` — returns a list
- `?cust_code=XXX` — returns customer detail + unpaid installments

The JS file dynamically renders tables and a slide-in detail panel.

### CSS

Each module has its own stylesheet (e.g., `ActionOptions/actionOptionsStyles.css`, `Search/searchStyles.css`). Cache-busting uses `filemtime()` as a query string version.
