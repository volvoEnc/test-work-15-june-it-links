# Task 4: HTTP README And Smoke Checks

**Risk:** high
**Depends on:** Task 3
**Review policy:** per-task

**Files:**
- Create: `modules/car/controllers/CarController.php`
- Modify: `config/web.php`, `README.md`
- Test: HTTP smoke via Docker runtime

## Steps

- [ ] Add controller and URL rules for exact required endpoints.
- [ ] Add stable JSON error rendering.
- [ ] Add README with Docker install/migrate/run/test commands and curl examples.
- [ ] Run migrations through Docker.
- [ ] Start app and run smoke checks for create, get, list, validation, invalid page, and not found.
- [ ] Commit with `feat: expose car api http endpoints`.
