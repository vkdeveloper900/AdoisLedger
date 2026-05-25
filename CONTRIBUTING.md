# Contributing to AdoisLedger

Thank you for your interest in contributing!

## Branching

- `main` — stable production branch
- `feature/<module>` — new features (e.g. `feature/reports`)
- `fix/<issue>` — bug fixes (e.g. `fix/ledger-balance`)

Always branch off `main`.

## Commit Format

```
type: short description

Examples:
feat: add dairy purchase module
fix: correct running balance on payment
docs: update README
refactor: extract PostBillAction
```

No `Co-Authored-By:` lines in commit messages.

## Code Standards

- PHP 8.3+ syntax
- Run `./vendor/bin/pint` before committing (Laravel Pint formatter)
- All money values stored as integers (paise — no floats)
- All queries must be scoped to `business_profile_id` — no cross-business data
- Ledger entries only via `PostBillAction` — never created directly in controllers

## Pull Requests

1. Fork the repo and create your branch from `main`
2. Make your changes
3. Run tests: `php artisan test`
4. Format code: `./vendor/bin/pint`
5. Open a PR with a clear description of what changed and why

## Database

- Add migrations for any schema changes — never modify existing migrations
- Update seeders if new tables need sample data
- Test with: `php artisan migrate:fresh --seed`

## Questions

Open a GitHub Issue or contact Adois Studio.
