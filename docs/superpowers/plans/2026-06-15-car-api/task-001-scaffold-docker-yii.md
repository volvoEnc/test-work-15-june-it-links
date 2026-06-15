# Task 1: Scaffold Docker And Yii

**Risk:** medium
**Depends on:** none
**Review policy:** group

**Files:**
- Create: `composer.json`, `Dockerfile`, `docker-compose.yml`, `.dockerignore`
- Create: `config/web.php`, `config/console.php`, `config/db.php`, `config/di.php`
- Create: `web/index.php`, `yii`, `tests/bootstrap.php`, `phpunit.xml`
- Modify: `.gitignore`

## Steps

- [ ] Add Docker Compose services for app, PostgreSQL, and Composer dependency install.
- [ ] Add Yii2 dependencies and PSR-4 autoloading.
- [ ] Add Yii web/console bootstrap, JSON parser, DB config, and DI include.
- [ ] Add PHPUnit bootstrap/config.
- [ ] Verify `composer install` inside Docker.
- [ ] Commit with `feat: add docker yii application scaffold`.
