# AdoisLedger

A self-hosted, multi-tenant accounting and ledger management system tailored for Indian small businesses — dairy farms, general stores, and construction material suppliers.

AdoisLedger tracks sales, purchases, vendor/customer payments, and maintains a full double-entry ledger per business. Multiple business profiles can be managed from a single installation, with session-scoped multi-tenancy, PDF bill generation, WhatsApp sharing, automated backups, and a clean Bootstrap 5 admin UI.

---

## Tech Stack

| Layer | Technology |
|-------|-----------|
| **Framework** | Laravel 13.8 (PHP 8.3+) |
| **UI Theme** | Vuexy v10 — Bootstrap 5 Admin Template |
| **Database** | MySQL 8+ (SQLite supported for local dev) |
| **PDF Generation** | barryvdh/laravel-dompdf v3.1 |
| **Session / Cache** | Database driver |
| **Queue** | Database driver |
| **Auth** | Laravel session-based auth (single user) |
| **Frontend** | Bootstrap 5, Tabler Icons, Vite |
| **Timezone** | Asia/Kolkata (IST) |

---

## Features

- **Multi-business support** — manage Dairy, General Store, and Construction businesses from one app
- **Business-type-aware billing** — dairy fat% tracking, construction materials & units, general sale
- **Double-entry ledger** — running balance per customer/vendor with full audit trail
- **PDF bills** — clean printable invoices via dompdf
- **WhatsApp sharing** — send bill summaries directly via WhatsApp
- **Payments** — record customer receipts and vendor payments with ledger posting
- **Reports** — balance sheet by business, per-customer ledger detail
- **Database backup & restore** — pure PHP backup (no mysqldump required), works offline
- **User management** — add/edit users with avatar upload
- **Documentation module** — built-in usage guide for all modules
- **Error pages** — Vuexy-themed 404, 401, 500, 503 pages

---

## Business Types

| ID | Type | Special Logic |
|----|------|--------------|
| 1 | General Store | Basic sale billing |
| 2 | Dairy | Fat % per item, dairy sale + purchase |
| 3 | Construction Materials | Materials & units management |

---

## Architecture Overview

```
┌─────────────────────────────────────────────┐
│               Browser / User                │
└──────────────────────┬──────────────────────┘
                       │
┌──────────────────────▼──────────────────────┐
│         Laravel Route + Middleware           │
│   ActiveBusinessProfile (session context)   │
└──────────────────────┬──────────────────────┘
                       │
        ┌──────────────┼──────────────┐
        │              │              │
   Controllers     Actions        Services
  (HTTP layer)  (PostBillAction)  (BackupService)
        │              │
        └──────────────▼
              Eloquent Models
     Transaction · LedgerEntry · Payment
     Customer · BusinessProfile · Material
        │
        ▼
   MySQL / SQLite Database
```

### Key Design Patterns

**Session Multi-tenancy** — `session('active_business_id')` scopes every query to the active `BusinessProfile`. The `ActiveBusinessProfile` middleware injects `$activeProfile` into all views.

**PostBillAction** — The single entry point for all ledger posting. Creates `LedgerEntry` records, calculates running balance, and marks transactions as `posted`. Never bypassed.

**Double-Entry Ledger** — Every financial event (sale, purchase, payment) writes a `LedgerEntry` with `debit`, `credit`, and `running_balance` stored as integers (paise — no floats).

---

## Database Schema

```
users                    → auth + profile (name, email, avatar, phone)
business_profiles        → business name, type, bank details
customers                → party (customer | vendor | both) per business
transactions             → bills & purchases (type enum)
transaction_items        → line items (qty, fat%, rate, amount)
ledger_entries           → double-entry records (debit, credit, running_balance)
payments                 → standalone payment records
materials                → construction items per business
units                    → measurement units per business
```

**Amount Storage:** All money columns (`total_amount`, `balance`, `debit`, `credit`, `running_balance`) are integers representing paise/cents. No float columns.

---

## Transaction Types (Enum)

| Type | Description |
|------|-------------|
| `general_sale` | General store billing |
| `dairy_sale` | Dairy customer billing (with fat%) |
| `dairy_purchase` | Dairy vendor purchase |
| `construction_sale` | Construction materials billing |

---

## Project Structure

```
app/
  Actions/Billing/PostBillAction.php     # Core ledger-posting logic
  Enums/TransactionType.php              # Transaction type enum
  Http/
    Controllers/
      Auth/AuthController.php
      Billing/BillController.php
      Billing/PaymentController.php
      Purchases/PurchaseController.php
      Parties/CustomerController.php
      Settings/BusinessProfileController.php
      Settings/UserController.php
      Settings/BackupController.php
      DocumentationController.php
      EnterShopController.php
    Middleware/ActiveBusinessProfile.php
  Models/
    BusinessProfile · Customer · Transaction
    TransactionItem · LedgerEntry · Payment
    Material · Unit · User
  Services/BackupService.php

resources/views/
  layouts/           # app.blade.php, auth.blade.php
  dashboard/
  billing/           # create-general, create-dairy, create-construction, show, ledger
  purchases/
  parties/customers/
  reports/           # balance-sheet, per-customer
  settings/          # business-profiles, users, backup
  docs/              # 5-page built-in documentation
  errors/            # 401, 404, 500, 503

database/
  migrations/
  seeders/
    DatabaseSeeder · BusinessProfileSeeder
    CustomerSeeder · MaterialUnitSeeder
```

---

## Getting Started

### Requirements

- PHP 8.3+
- MySQL 8+ (or SQLite for local dev)
- Composer
- Node.js + NPM

### Installation

```bash
# Clone the repo
git clone https://github.com/vkdeveloper900/AdoisLedger.git
cd AdoisLedger

# Install dependencies and set up
composer run setup
# (runs: composer install, key:generate, migrate, npm install, npm run build)

# Or manually:
composer install
cp .env.example .env
php artisan key:generate
# Edit .env — set DB_DATABASE, DB_USERNAME, DB_PASSWORD
php artisan migrate
php artisan db:seed
npm install && npm run build
```

### Environment

Key `.env` settings:

```env
APP_TIMEZONE=Asia/Kolkata

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=adois_ledger
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

### Default Login (after seeding)

| Field | Value |
|-------|-------|
| Email | `admin@gmail.com` |
| Password | `Admin@123` |

### Sample Businesses (seeded)

| Business | Type |
|----------|------|
| Ranisa Dairy | Dairy |
| Ranisa General Store | General |
| Ranisa Construction Materials | Construction |

---

## Development

```bash
# Start dev server (server + queue + logs + vite)
composer run dev

# Run tests
php artisan test

# Format code
./vendor/bin/pint

# Re-seed database (truncates all tables)
php artisan db:seed
```

---

## Core Flow

```
Login → Select Business → Dashboard
  ├── Billing → Create Bill → PostBillAction → LedgerEntry (receivable)
  ├── Purchases → Create Purchase → PostBillAction → LedgerEntry (payable)
  ├── Payments → Record Payment → LedgerEntry (payment_received / payment_made)
  ├── Reports → Balance Sheet / Per-Customer Ledger
  └── Settings → Business Profiles / Users / Backup & Restore
```

---

## License

Private project. All rights reserved.
