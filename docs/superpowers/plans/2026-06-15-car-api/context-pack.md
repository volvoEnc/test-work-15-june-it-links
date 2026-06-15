# Context Pack

**Spec:** `docs/superpowers/specs/2026-06-15-car-api-design.md`

## Repository Map

| Path | Responsibility | Why it matters |
|------|----------------|----------------|
| `.gitignore` | Ignore local/generated files | Must keep `vendor`, secrets, runtime output out of commits |
| `car_api_yii2_technical_spec.md` | Root task file, ignored | Source requirement document, not intended for commit |
| `composer.json` | PHP dependencies/autoload/scripts | Needed for Yii2 app and PHPUnit |
| `Dockerfile` | PHP/Apache runtime image | Dependencies must be installed inside Docker |
| `docker-compose.yml` | App/PostgreSQL/test workflow | Main local runtime and verification surface |
| `config/` | Yii web/console/db/DI config | Routes, JSON parser, DB, DI bindings |
| `web/index.php` | Web entrypoint | Serves API through Yii |
| `yii` | Console entrypoint | Runs migrations/tests helper commands |
| `migrations/` | DB schema | Required PostgreSQL constraints |
| `modules/car/` | Car API layers | Core assignment implementation |
| `tests/` | PHPUnit tests | TDD and verification |
| `README.md` | Operator docs | Required Docker commands and curl examples |

## Existing Patterns

- Greenfield repo; no app code exists.
- Use Yii2 basic-style app conventions at repo root.
- Avoid ActiveRecord for domain/persistence.

## Constraints

- Work in `master`.
- Use Docker for dependency install/runtime/tests.
- Do not commit `vendor`, secrets, IDE files, temp files.
- Use fixed page size `20`.
- `options` absent/null means no `car_option`; object means all option fields required.

## Test Commands

- `docker compose run --rm composer install`
- `docker compose run --rm app vendor/bin/phpunit`
- `docker compose run --rm app php yii migrate --interactive=0`
- HTTP smoke checks against `http://localhost:8080` after `docker compose up -d app`

## Risk Triggers

- Yii routing must preserve `/car/create`, `/car/{id}`, `/car/list`.
- Tests need dependencies installed inside container.
- Decimal `price` should not be normalized through business math.
- Validation errors must preserve nested field paths such as `options.brand`.
- PostgreSQL constraints must match form validation.

## Open Questions

- None blocking.
