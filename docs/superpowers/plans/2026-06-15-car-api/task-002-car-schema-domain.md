# Task 2: Car Schema And Domain

**Risk:** high
**Depends on:** Task 1
**Review policy:** per-task

**Files:**
- Create: `migrations/*_create_car_tables.php`
- Create: `modules/car/entities/*`
- Create: `modules/car/mappers/*`
- Create: `modules/car/repositories/*`
- Test: `tests/unit/modules/car/*`

## Steps

- [ ] Write failing mapper/repository tests for rows, optional options, and persistence.
- [ ] Verify RED through Docker PHPUnit.
- [ ] Add migration with PostgreSQL constraints.
- [ ] Add plain PHP entities, mapper, repository interface, and Yii DB implementation.
- [ ] Verify tests GREEN through Docker PHPUnit.
- [ ] Commit with `feat: add car schema domain persistence`.
