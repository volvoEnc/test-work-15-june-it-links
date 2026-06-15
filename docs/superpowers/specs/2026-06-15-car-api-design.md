# Root Yii2 Car API Design

## Goal

Build a new root-level PHP 8/Yii2/PostgreSQL car API. The project lives at repo root on `master`, runs through Docker/Docker Compose, and exposes create, read, and paginated list endpoints for cars.

## Scope

Implement:

- `POST /car/create`
- `GET /car/{id}`
- `GET /car/list?page=1`
- Yii2 basic-style app scaffold at repo root
- Dockerfile, `docker-compose.yml`, Composer setup, Yii config, web entrypoint, console entrypoint
- Yii migration for required `car` and `car_option` tables; creating a `car_option` row is optional per request
- Layered Car API code under `modules/car`
- README with Docker commands and curl examples
- Practical PHPUnit coverage for form, service, mapper/repository, and response behavior

Out of scope:

- Update/delete endpoints
- `yii\rest\ActiveController`
- ActiveRecord domain CRUD
- Host dependency installation
- Committing `vendor`, secrets, IDE files, temp files

## Architecture

Use explicit layers:

- Controller: HTTP entrypoint, delegates validation and application behavior
- Request/Form: validates incoming payload/query params
- Command DTO: immutable create/list input passed to service
- Service: transaction boundary and business orchestration
- Repository interface: domain persistence contract
- Repository implementation: Yii DB command/query builder only
- Entity: plain PHP domain object, not ActiveRecord
- DataMapper: maps DB rows to entities and persistence arrays
- Response Assembler: converts entities to API response arrays

Recommended structure:

- `modules/car/controllers/CarController.php`
- `modules/car/forms/CreateCarForm.php`
- `modules/car/forms/ListCarForm.php`
- `modules/car/commands/CreateCarCommand.php`
- `modules/car/services/CarService.php`
- `modules/car/repositories/CarRepositoryInterface.php`
- `modules/car/repositories/YiiDbCarRepository.php`
- `modules/car/entities/Car.php`
- `modules/car/entities/CarOption.php`
- `modules/car/mappers/CarDataMapper.php`
- `modules/car/assemblers/CarResponseAssembler.php`

Bind repository interface to implementation in Yii DI config.

## Data Model

Use PostgreSQL tables:

`car`

- `id` primary key
- `title varchar(255) not null`
- `description text not null`
- `price decimal(12,2) not null`; stored as decimal/string internally
- `photo_url varchar(2048) not null`
- `contacts varchar(255) not null`
- `created_at timestamp not null default current timestamp`

`car_option`

- `id` primary key
- `car_id` foreign key references `car(id)` on delete cascade
- `brand varchar(255) not null`
- `model varchar(255) not null`
- `year integer not null`
- `body varchar(255) not null`
- `mileage integer not null`
- unique constraint on `car_id`
- all option fields required when an `options` object is provided

Constraints:

- non-negative `price`
- non-negative `car_option.mileage`
- bounded `car_option.year`
- unique option row per car
- FK cascade from `car_option` to `car`

## Endpoint Behavior

`POST /car/create`

- Accept JSON body.
- Validate all required car fields.
- `options` absent or `null`: create only `car`.
- `options` object: all option fields are required; create `car` and `car_option` in one transaction.
- On success return created car response, including `options: null` when no options exist.
- Response status: `201`.

`GET /car/{id}`

- Look up car by integer id.
- Return assembled car response.
- Include `options` object if present, otherwise `null`.
- Missing car returns `404`.

`GET /car/list?page=1`

- Validate `page` as positive integer, default `1`.
- Use fixed page size `20`.
- Return shape:

```json
{
  "items": [],
  "pagination": {
    "page": 1,
    "per_page": 20,
    "total": 0,
    "pages": 0
  }
}
```

- Order list results by `created_at DESC, id DESC`.
- Each item uses the same assembler shape as single-car response.

## Error Handling

- Invalid JSON: `400`.
- Validation failures: `422` with field-level errors:

```json
{
  "error": "Validation failed",
  "fields": {
    "price": ["Price must be greater than or equal to 0."],
    "options.brand": ["Brand cannot be blank."]
  }
}
```

- Invalid page: `400`.

```json
{
  "error": "Invalid page parameter"
}
```

- Missing resource: `404`.

```json
{
  "error": "Car not found"
}
```

- Unexpected persistence/runtime failure: `500` with generic message, no secrets.

```json
{
  "error": "Internal server error"
}
```

- DB constraint violations should be caught where practical and converted to stable API errors.
- Create operation must use a DB transaction; rollback on any car/option failure.

## Testing Strategy

Use Docker for all test execution.

Cover:

- `CreateCarForm`
  - required fields
  - invalid `photo_url` length over `2048`
  - negative price/mileage rejected
  - invalid year rejected
  - absent/null `options` accepted
  - partial `options` object rejected
- `CarService`
  - creates car without options
  - creates car with options in transaction
  - surfaces not-found behavior for missing id
- `CarResponseAssembler`
  - emits expected single-car shape
  - emits `options: null` when absent
- Repository/mapper
  - DB row maps to entity
  - entity/options persist and read back correctly
- HTTP smoke checks through Docker after migrations.

## README Requirements

Include:

- prerequisites: Docker/Docker Compose
- build/start commands
- dependency installation through Docker
- migration command through Docker
- test command through Docker
- curl examples for create, get by id, and list
- note that dependencies are not installed on host
- expected response examples, including `{ items, pagination }`

## Git/Workflow Requirements

- Work on branch `master`.
- Commit along the way.
- Commit messages must match `feat: description`.
- Description should be 5-12 words.
- Do not commit `vendor`, secrets, IDE/temp files.

## Acceptance Criteria

- Repo root contains a runnable Yii2 basic-style API app.
- Docker Compose can build, install dependencies, run migrations, start app, and run tests.
- `POST /car/create` creates cars with or without options using transactions.
- `GET /car/{id}` returns the created car or `404`.
- `GET /car/list?page=1` returns `{ items, pagination }` with fixed `per_page: 20`.
- Domain entities are plain PHP objects, not ActiveRecord.
- Persistence uses Yii DB commands/query builder through repository and mapper.
- PostgreSQL constraints enforce FK cascade, unique option per car, non-negative price/mileage, bounded year, and `photo_url` max length.
- Validation and error responses are stable and documented.
- README includes working Docker commands and curl examples.
- Tests cover the practical form/service/assembler/repository behavior described above.
