# Car API Yii2

Dockerized PHP 8/Yii2 REST API for car listings backed by PostgreSQL.

## Requirements

- Docker
- Docker Compose

Dependencies are installed inside Docker and written to the project through the mounted volume. Do not run Composer on the host.

## Setup

```bash
docker compose run --rm composer install
docker compose run --rm app php yii migrate --interactive=0
```

## Run

```bash
docker compose up -d app
```

The API is available at `http://localhost:18080`.

## Test

```bash
docker compose run --rm app vendor/bin/phpunit
```

## Endpoints

- `POST /car/create`
- `GET /car/{id}`
- `GET /car/list?page=1`

The list endpoint always uses `per_page: 20`.

## Curl Examples

Create without options:

```bash
curl -i -X POST http://localhost:18080/car/create \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Toyota Camry",
    "description": "Good condition",
    "price": 1500000,
    "photo_url": "https://example.com/camry.jpg",
    "contacts": "+7 999 000-00-00"
  }'
```

Create with options:

```bash
curl -i -X POST http://localhost:18080/car/create \
  -H "Content-Type: application/json" \
  -d '{
    "title": "BMW 320i",
    "description": "Clean car",
    "price": 2200000,
    "photo_url": "https://example.com/bmw.jpg",
    "contacts": "+7 999 111-22-33",
    "options": {
      "brand": "BMW",
      "model": "320i",
      "year": 2021,
      "body": "sedan",
      "mileage": 30000
    }
  }'
```

Get by id:

```bash
curl -i http://localhost:18080/car/1
```

List:

```bash
curl -i http://localhost:18080/car/list?page=1
```

## Response Shape

Single car:

```json
{
  "id": 1,
  "title": "BMW 320i",
  "description": "Clean car",
  "price": 2200000,
  "photo_url": "https://example.com/bmw.jpg",
  "contacts": "+7 999 111-22-33",
  "options": {
    "brand": "BMW",
    "model": "320i",
    "year": 2021,
    "body": "sedan",
    "mileage": 30000
  }
}
```

List:

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

Validation error:

```json
{
  "error": "Validation failed",
  "fields": {
    "title": ["Title cannot be blank."],
    "options.brand": ["Brand cannot be blank."]
  }
}
```
